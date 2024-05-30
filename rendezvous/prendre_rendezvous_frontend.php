<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un rendez-vous</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            text-align: center;
            padding: 10px;
        }
        th {
            width: 20%;
        }
        td {
            width: 20%;
        }
        .speciality-col, .doctor-col {
            width: 10%;
        }
        .available {
            background-color: yellow;
            cursor: pointer;
        }
        .unavailable {
            background-color: blue;
            color: white;
        }
        .time-slot {
            padding: 15px;
        }
    </style>
</head>
<body>
    <h1>Prendre un rendez-vous</h1>
    
    <!-- Affichage du calendrier des créneaux -->
    <form id="rendezvousForm" action="prendre_rendezvous_backend.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th class="speciality-col">Spécialité</th>
                    <th class="doctor-col">Médecin</th>
                    <th>Lundi</th>
                    <th>Mercredi</th>
                    <th>Vendredi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connexion à la base de données
                $servername = "localhost";
                $username = "Maxime";
                $password = "max";
                $dbname = "projet_piscine";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("La connexion a échoué : " . $conn->connect_error);
                }

                // Requête SQL pour récupérer les disponibilités des coachs
                $sql = "SELECT * FROM disponibilites WHERE coach_id = 2";
                $result = $conn->query($sql);

                $slots = [];
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $slots[$row['debut']] = $row['disponible'];
                    }
                }

                // Affichage des créneaux
                $times = ["09:00:00", "09:20:00", "09:40:00", "10:00:00", "10:20:00", "10:40:00", "11:00:00", "11:20:00", "11:40:00", "12:00:00", 
                          "14:00:00", "14:20:00", "14:40:00", "15:00:00", "15:20:00", "15:40:00", "16:00:00", "16:20:00", "16:40:00", "17:00:00",
                          "17:20:00", "17:40:00", "18:00:00"];
                $days = ["Lundi" => "2024-06-01", "Mercredi" => "2024-06-03", "Vendredi" => "2024-06-05"];
                
                foreach ($times as $index => $time) {
                    echo "<tr>";
                    if ($index == 0) {
                        echo "<td rowspan='".count($times)."'>Coach, Musculation</td>";
                        echo "<td rowspan='".count($times)."'>DUMAIS, Guy</td>";
                    }
                    foreach ($days as $day => $date) {
                        $slotKey = $date . " " . $time;
                        $isAvailable = isset($slots[$slotKey]) && $slots[$slotKey] ? true : false;
                        $class = $isAvailable ? "available" : "unavailable";
                        echo "<td class='$class time-slot' data-time='$slotKey'>$time</td>";
                    }
                    echo "</tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <input type="hidden" id="selectedSlot" name="selectedSlot" value="">
    </form>

    <script>
        document.querySelectorAll('.time-slot.available').forEach(slot => {
            slot.addEventListener('click', function() {
                document.getElementById('selectedSlot').value = this.getAttribute('data-time');
                document.getElementById('rendezvousForm').submit();
            });
        });
    </script>
</body>
</html>
