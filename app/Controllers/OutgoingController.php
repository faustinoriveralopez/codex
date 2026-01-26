<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\OutgoingDocument;
use App\Models\Document;

class OutgoingController extends Controller {
    public function __construct() {
        $this->requireAuth();
    }

    public function index() {
        $model = new OutgoingDocument();
        $sql = "SELECT d.*, u.name as creator_name, inc.internal_folio as incoming_folio
                FROM outgoing_documents d
                LEFT JOIN users u ON d.created_by = u.id
                LEFT JOIN documents inc ON d.related_incoming_id = inc.id
                ORDER BY d.created_at DESC";
        $docs = $model->query($sql)->fetchAll();

        $this->view('outgoing/index', ['title' => 'Oficios de Salida', 'docs' => $docs]);
    }

    public function create() {
        $data = ['title' => 'Registrar Salida'];

        // Check if pre-linked from incoming
        if (isset($_GET['reply_to'])) {
            $incomingId = $_GET['reply_to'];
            $docModel = new Document();
            $inc = $docModel->find($incomingId);
            if ($inc) {
                $data['related_incoming'] = $inc;
            }
        }

        $this->view('outgoing/create', $data);
    }

    public function store() {
        $subject = $_POST['subject'] ?? '';
        $recipient = $_POST['recipient_name'] ?? '';

        if (empty($subject) || empty($recipient)) {
             $this->redirect('outgoing/create?error=missing_fields');
             return;
        }

        $year = date('Y');
        $model = new OutgoingDocument();
        $seqData = $model->getNextFolio($year);
        $folio = $seqData['folio'];

        // File Upload (Optional but recommended for output)
        $filepath = null;
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['pdf_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $uploadDir = 'public/uploads/outgoing/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $filename = $folio . '_' . time() . '.pdf';
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $filepath = $dest;
                }
            }
        }

        $id = $model->create([
            'folio' => $folio,
            'recipient_name' => $recipient,
            'recipient_dependency' => $_POST['recipient_dependency'] ?? '',
            'subject' => $subject,
            'description' => $_POST['description'] ?? '',
            'related_incoming_id' => !empty($_POST['related_incoming_id']) ? $_POST['related_incoming_id'] : null,
            'created_by' => $_SESSION['user_id'],
            'filepath' => $filepath
        ]);

        if ($id) {
            $model->incrementSequence($year, $seqData['number']);
            $this->redirect('outgoing/index');
        } else {
             $this->redirect('outgoing/create?error=db_error');
        }
    }
}
