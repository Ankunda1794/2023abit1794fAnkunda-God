<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editable School Timetable (S1–S6)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td { vertical-align: top; text-align: center; }
        input { width: 100%; padding: 4px; font-size: 0.9rem; }
        .time-slot { width: 150px; font-weight: bold; background-color: #f8f9fa; }
        h4 { background: #dee2e6; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">📘 Weekly School Timetable (S1–S6)</h2>

    <form action="save_timetable.php" method="POST">
        <?php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $classes = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'];

        // Generate 80-minute time slots from 8:00 to 6:00 (last starts at 4:40)
        function generateTimeSlots($start = "08:00", $end = "18:00", $duration = 80) {
            $slots = [];
            $startTime = strtotime($start);
            $endTime = strtotime($end);
            while ($startTime + ($duration * 60) <= $endTime) {
                $endSlot = $startTime + ($duration * 60);
                $slots[] = date("H:i", $startTime) . " - " . date("H:i", $endSlot);
                $startTime = $endSlot;
            }
            return $slots;
        }

        $periods = generateTimeSlots();

        foreach ($days as $day): ?>
            <h4 class="mt-5"><?= $day ?></h4>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                <tr>
                    <th class="time-slot">Time</th>
                    <?php foreach ($classes as $class): ?>
                        <th><?= $class ?><br><small>Subject / Teacher</small></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($periods as $time): ?>
                    <tr>
                        <td class="time-slot"><?= $time ?></td>
                        <?php foreach ($classes as $class): ?>
                            <td>
                                <input type="text" name="timetable[<?= $day ?>][<?= $time ?>][<?= $class ?>][subject]" placeholder="Subject">
                                <input type="text" name="timetable[<?= $day ?>][<?= $time ?>][<?= $class ?>][teacher]" placeholder="Teacher">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary mt-4">Save Timetable</button>
    </form>
</div>
</body>
</html>
