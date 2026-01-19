<?php
namespace App\Helpers;

use DateTime;

class DateHelper {
    /**
     * Calculates a future date by adding business days (skipping Sat/Sun).
     *
     * @param string $startDate Y-m-d format
     * @param int $days Number of business days to add
     * @return string Y-m-d format
     */
    public static function addBusinessDays($startDate, $days) {
        $date = new DateTime($startDate);

        // If 0 days, return same day
        if ($days == 0) return $date->format('Y-m-d');

        $currentDay = 0;

        while ($currentDay < $days) {
            $date->modify('+1 day');
            $dayOfWeek = $date->format('N'); // 1 (Mon) to 7 (Sun)

            // Skip Saturday (6) and Sunday (7)
            if ($dayOfWeek < 6) {
                $currentDay++;
            }
        }

        return $date->format('Y-m-d');
    }

    /**
     * Determines the semaphore status based on deadline.
     *
     * @param string $deadlineDate Y-m-d
     * @return string 'ROJO', 'AMARILLO', 'VERDE'
     */
    public static function getSemaphoreStatus($deadlineDate) {
        if (!$deadlineDate) return 'VERDE';

        $deadline = new DateTime($deadlineDate . ' 23:59:59');
        $now = new DateTime();

        // Check if already passed
        if ($now > $deadline) {
            return 'ROJO';
        }

        // Check days remaining
        $interval = $now->diff($deadline);
        $daysRemaining = (int)$interval->format('%r%a');

        if ($daysRemaining <= 3) {
            return 'AMARILLO';
        }

        return 'VERDE';
    }
}
