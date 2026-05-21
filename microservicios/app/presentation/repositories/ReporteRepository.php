<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../domain/models/Reporte.php';

class ReporteRepository {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function generar(int $sprint_id): Reporte {
        $stmt = $this->conn->prepare(
            "SELECT
                COUNT(*)                                                  AS total_historias,
                SUM(CASE WHEN estado = 'finalizada'  THEN 1 ELSE 0 END)  AS finalizadas,
                SUM(CASE WHEN estado = 'activa'      THEN 1 ELSE 0 END)  AS activas,
                SUM(CASE WHEN estado = 'nueva'       THEN 1 ELSE 0 END)  AS nuevas,
                SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END)  AS impedimentos,
                COALESCE(SUM(puntos), 0)                                  AS puntos_totales
             FROM historias
             WHERE sprint_id = :sprint_id"
        );
        $stmt->execute(['sprint_id' => $sprint_id]);
        $totales = $stmt->fetch();

        $total       = (int)$totales['total_historias'];
        $finalizadas = (int)$totales['finalizadas'];
        $velocidad   = $total > 0 ? round(($finalizadas / $total) * 100, 2) : 0.0;

        $stmt2 = $this->conn->prepare(
            "SELECT
                responsable,
                COUNT(*)                                                  AS total,
                SUM(CASE WHEN estado = 'finalizada'  THEN 1 ELSE 0 END)  AS finalizadas,
                SUM(CASE WHEN estado = 'activa'      THEN 1 ELSE 0 END)  AS activas,
                SUM(CASE WHEN estado = 'nueva'       THEN 1 ELSE 0 END)  AS nuevas,
                SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END)  AS impedimentos,
                COALESCE(SUM(puntos), 0)                                  AS puntos_totales
             FROM historias
             WHERE sprint_id = :sprint_id
             GROUP BY responsable
             ORDER BY responsable ASC"
        );
        $stmt2->execute(['sprint_id' => $sprint_id]);
        $porResponsable = array_map(function (array $r): array {
            $r['total']          = (int)$r['total'];
            $r['finalizadas']    = (int)$r['finalizadas'];
            $r['activas']        = (int)$r['activas'];
            $r['nuevas']         = (int)$r['nuevas'];
            $r['impedimentos']   = (int)$r['impedimentos'];
            $r['puntos_totales'] = (int)$r['puntos_totales'];
            $r['velocidad']      = $r['total'] > 0
                ? round(($r['finalizadas'] / $r['total']) * 100, 2)
                : 0.0;
            return $r;
        }, $stmt2->fetchAll());

        return new Reporte(
            $total,
            $finalizadas,
            (int)$totales['activas'],
            (int)$totales['nuevas'],
            (int)$totales['impedimentos'],
            (int)$totales['puntos_totales'],
            $velocidad,
            $porResponsable
        );
    }
}
