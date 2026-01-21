<?php
namespace App\Models;

class HakemDegerlendirme extends BaseModel {
    protected $table = 'hakem_degerlendirme';

    /**
     * Save full evaluation with criteria scores
     */
    public function saveEvaluation($assignmentId, $data, $scores) {
        try {
            $this->pdo->beginTransaction();

            // 1. Save main evaluation
            $sql = "INSERT INTO {$this->table} (makale_hakem_id, karar, notlar_yazar, notlar_editor, dosya) 
                    VALUES (:mhid, :karar, :ny, :ne, :dosya)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'mhid' => $assignmentId,
                'karar' => $data['karar'],
                'ny' => $data['notlar_yazar'],
                'ne' => $data['notlar_editor'],
                'dosya' => $data['dosya'] ?? null
            ]);
            
            $evaluationId = $this->pdo->lastInsertId();

            // 2. Save criteria scores
            $scoreSql = "INSERT INTO hakem_degerlendirme_cevap (degerlendirme_id, kriter_id, puan) VALUES (:eid, :kid, :puan)";
            $scoreStmt = $this->pdo->prepare($scoreSql);

            foreach ($scores as $kriterId => $puan) {
                $scoreStmt->execute([
                    'eid' => $evaluationId,
                    'kid' => $kriterId,
                    'puan' => $puan
                ]);
            }

            // 3. Update assignment status to Completed
            $updateSql = "UPDATE makale_hakem SET status = 3, updated_at = NOW() WHERE id = :id";
            $this->pdo->prepare($updateSql)->execute(['id' => $assignmentId]);

            $this->pdo->commit();
            return $evaluationId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
