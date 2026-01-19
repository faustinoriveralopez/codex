<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Document;
use App\Models\Area;
use App\Models\DocumentHistory;

class DocumentController extends Controller {
    public function __construct() {
        $this->requireAuth();
    }

    public function create() {
        $data = ['title' => 'Registrar Nuevo Oficio'];
        $this->view('documents/create', $data);
    }

    public function store() {
        $subject = $_POST['subject'] ?? '';
        $sender = $_POST['sender_dependency'] ?? '';

        if (empty($subject) || empty($sender)) {
             $this->view('documents/create', ['error' => 'El Asunto y la Dependencia Remitente son obligatorios.', 'title' => 'Registrar Nuevo Oficio']);
             return;
        }

        $docModel = new Document();
        $folio = $docModel->getNextInternalFolio(date('Y'));

        $data = [
            'internal_folio' => $folio,
            'external_folio' => $_POST['external_folio'] ?? '',
            'subject' => $subject,
            'description' => $_POST['description'] ?? '',
            'doc_type' => $_POST['doc_type'] ?? 'OFICIO',
            'sender_dependency' => $sender,
            'sender_name' => $_POST['sender_name'] ?? '',
            'priority' => $_POST['priority'] ?? 'NORMAL',
            'status' => 'RECIBIDO',
            'created_by' => $_SESSION['user_id'],
            'current_area_id' => $_SESSION['area_id'],
            'current_user_id' => $_SESSION['user_id']
        ];

        $id = $docModel->create($data);

        if ($id) {
            // Handle File Upload
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $file = $_FILES['pdf_file'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                // Basic validation
                if (strtolower($ext) === 'pdf') {
                    $filename = $folio . '_' . time() . '.pdf';
                    $filepath = $uploadDir . $filename;

                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        // Save to attachments table
                        $sql = "INSERT INTO attachments (document_id, filename, filepath) VALUES (:doc_id, :fname, :fpath)";
                        // We use the model's query method which uses the db connection
                        $docModel->query($sql, [
                            'doc_id' => $id,
                            'fname' => $file['name'],
                            'fpath' => $filepath
                        ]);
                    }
                }
            }

            // Log creation
            $histModel = new DocumentHistory();
            $histModel->create([
                'document_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'action' => 'REGISTRO',
                'comment' => 'Documento registrado en sistema.'
            ]);

            $this->redirect('documents/reception');
        } else {
             $this->view('documents/create', ['error' => 'Error al guardar en base de datos.', 'title' => 'Registrar Nuevo Oficio']);
        }
    }

    public function reception() {
        $docModel = new Document();
        $sql = "SELECT d.*, u.name as created_by_name, a.name as current_area_name
                FROM documents d
                LEFT JOIN users u ON d.created_by = u.id
                LEFT JOIN areas a ON d.current_area_id = a.id
                ORDER BY d.created_at DESC";
        $documents = $docModel->query($sql)->fetchAll();

        $this->view('documents/reception', ['title' => 'Mesa de Control', 'documents' => $documents]);
    }

    public function my_tray() {
        $area_id = $_SESSION['area_id'];
        $user_id = $_SESSION['user_id'];

        $docModel = new Document();
        // Documents assigned to my area or directly to me
        // Simple filter
        $sql = "SELECT d.*, u.name as created_by_name
                FROM documents d
                LEFT JOIN users u ON d.created_by = u.id
                WHERE d.current_user_id = :uid
                   OR (d.current_area_id = :aid)
                ORDER BY d.created_at DESC";

        $documents = $docModel->query($sql, ['uid' => $user_id, 'aid' => $area_id])->fetchAll();

        $this->view('documents/index', ['title' => 'Mi Bandeja', 'documents' => $documents]);
    }

    public function turnar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('documents/reception');
        }

        $docModel = new Document();
        $doc = $docModel->find($id);

        $areaModel = new Area();
        $areas = $areaModel->all();

        $this->view('documents/turnar', ['title' => 'Turnar Oficio', 'doc' => $doc, 'areas' => $areas]);
    }

    public function processTurnar() {
        $doc_id = $_POST['document_id'];
        $area_id = $_POST['area_id'];
        $comment = $_POST['comment'] ?? '';

        $docModel = new Document();
        $docModel->update($doc_id, [
            'current_area_id' => $area_id,
            'status' => 'TURNADO',
            'current_user_id' => null // Unassign specific user, assign to area
        ]);

        // History
        $histModel = new DocumentHistory();
        $histModel->create([
            'document_id' => $doc_id,
            'user_id' => $_SESSION['user_id'],
            'action' => 'TURNADO',
            'comment' => $comment
        ]);

        $this->redirect('documents/reception');
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('documents/reception');
        }

        $docModel = new Document();
        $doc = $docModel->getWithDetails($id);

        if (!$doc) {
             die("Documento no encontrado");
        }

        // Get attachments
        $attSql = "SELECT * FROM attachments WHERE document_id = :id";
        $attachments = $docModel->query($attSql, ['id' => $id])->fetchAll();

        // Get history
        $histSql = "SELECT h.*, u.name as user_name FROM document_history h LEFT JOIN users u ON h.user_id = u.id WHERE document_id = :id ORDER BY created_at DESC";
        $history = $docModel->query($histSql, ['id' => $id])->fetchAll();

        $this->view('documents/view', [
            'title' => 'Detalle del Oficio',
            'doc' => $doc,
            'attachments' => $attachments,
            'history' => $history
        ]);
    }
}
