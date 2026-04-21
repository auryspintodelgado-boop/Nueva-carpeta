<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTotalScoreToEvaluaciones extends Migration
{
    public function up()
    {
        $fields = [
            'total_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'   => 0,
                'after'     => 'updated_at',
            ],
        ];
        
        $this->forge->addColumn('evaluaciones', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('evaluaciones', 'total_score');
    }
}