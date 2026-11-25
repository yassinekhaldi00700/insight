<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * All application tables (evaluation flow, objectives, results...) plus the
 * reference data the forms need: question categories, questions and roles.
 * Tables that already exist are skipped, so this is safe on an existing database.
 */
return new class extends Migration
{
    private function stamps(Blueprint $table): void
    {
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    }

    private function create(string $name, callable $columns): void
    {
        if (!Schema::hasTable($name)) {
            Schema::create($name, $columns);
        }
    }

    public function up(): void
    {
        $this->create('categories', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('name');
        });

        $this->create('questions', function (Blueprint $t) {
            $t->integer('question_id', true);
            $t->string('question_text', 500);
            $t->integer('categorie_id')->nullable();
            $t->foreign('categorie_id', 'fk_questions_categories')->references('id')->on('categories')->cascadeOnDelete();
        });

        $this->create('roles', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('role_name')->unique('role_name');
        });

        $this->create('responses', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('user_id');
            $t->integer('categorie_id');
            $t->integer('question_id');
            $t->string('response_value')->nullable();
            $this->stamps($t);
            $t->string('role', 50)->nullable();
        });

        $this->create('user_progress', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('user_id');
            $t->integer('current_page');
            $t->string('role', 50);
            $this->stamps($t);
        });

        $this->create('user_results', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('user_id')->nullable();
            $t->string('result')->nullable();
            $t->string('role')->nullable();
            $this->stamps($t);
        });

        $this->create('evaluations', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('user_id');
            $t->tinyInteger('assiduite');
            $t->tinyInteger('disponibilite');
            $this->stamps($t);
            $t->string('role')->nullable();
            $t->float('average')->nullable();
        });

        $this->create('finalresult', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('userid');
            $t->float('resultn0')->nullable();
            $t->float('resultn1')->nullable();
            $t->float('resultn2')->nullable();
            $t->float('addedvalue')->nullable();
            $this->stamps($t);
            $t->string('final_result')->nullable();
        });

        $this->create('appreciations', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('user_id', 50)->nullable();
            $t->string('potential', 50)->nullable();
            $t->text('comment');
            $this->stamps($t);
            $t->string('role')->nullable();
        });

        $this->create('opportunites', function (Blueprint $t) {
            $t->integer('id', true);
            $t->integer('user_id');
            $t->string('court_terme')->nullable();
            $t->string('long_terme')->nullable();
            $this->stamps($t);
        });

        $this->create('opportunities1', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('user_id', 50)->nullable();
            $t->string('avis', 50)->nullable();
            $t->text('commentaire')->nullable();
            $this->stamps($t);
        });

        $this->create('qualitative_objectives', function (Blueprint $t) {
            $t->bigInteger('id', true);
            $t->bigInteger('user_id')->nullable();
            $t->text('objective')->nullable();
            $t->text('description')->nullable();
            $t->integer('weight')->nullable();
            $this->stamps($t);
        });

        $this->create('quantitative_objectives', function (Blueprint $t) {
            $t->bigInteger('id', true);
            $t->bigInteger('user_id')->nullable();
            $t->text('objective')->nullable();
            $t->text('description')->nullable();
            $t->integer('quantity')->nullable();
            $t->integer('weight')->nullable();
            $this->stamps($t);
        });

        $this->create('signature', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('fname')->nullable();
            $t->string('contenu')->nullable();
            $this->stamps($t);
            $t->integer('user_id')->nullable();
            $t->string('role')->nullable();
        });

        $this->create('entretien_date', function (Blueprint $t) {
            $t->integer('id', true);
            $t->string('role')->nullable();
            $t->integer('user_id');
            $t->date('date_entretien');
            $t->time('heure_entretien');
            $this->stamps($t);
        });

        // Reference data (only inserted into empty tables)
        if (DB::table('categories')->doesntExist()) {
            DB::table('categories')->insert(array (
              0 => 
              array (
                'id' => 1,
                'name' => 'Développement des compétences & aptitudes transverses pour être efficace, innovant et accélérer l\'évolution',
              ),
              1 => 
              array (
                'id' => 2,
                'name' => 'Incarnation des valeurs et des comportements',
              ),
              2 => 
              array (
                'id' => 3,
                'name' => 'Management',
              ),
            ));
        }
        if (DB::table('questions')->doesntExist()) {
            DB::table('questions')->insert(array (
              0 => 
              array (
                'question_id' => 1,
                'question_text' => 'Célérité d’exécution / de réalisation',
                'categorie_id' => 1,
              ),
              1 => 
              array (
                'question_id' => 2,
                'question_text' => 'Communication orale et aisance relationnelle (Communication interpersonnelle, intelligence émotionnelle, capacité d’écoute,…)',
                'categorie_id' => 1,
              ),
              2 => 
              array (
                'question_id' => 3,
                'question_text' => 'Culture du résultat et orientation étudiant',
                'categorie_id' => 1,
              ),
              3 => 
              array (
                'question_id' => 4,
                'question_text' => 'Capacité rédactionnelle',
                'categorie_id' => 1,
              ),
              4 => 
              array (
                'question_id' => 5,
                'question_text' => 'Démarche de diagnostic, qualité d’analyse et esprit de synthèse.',
                'categorie_id' => 1,
              ),
              5 => 
              array (
                'question_id' => 6,
                'question_text' => 'Planification et sens de l’organisation',
                'categorie_id' => 1,
              ),
              6 => 
              array (
                'question_id' => 7,
                'question_text' => 'Maitrise des fonctionnalités Konosys et des applications de la bureautique, Web, mobile, digitales et multicanales.',
                'categorie_id' => 1,
              ),
              7 => 
              array (
                'question_id' => 8,
                'question_text' => 'Organisation et gestion de la performance individuelle',
                'categorie_id' => 1,
              ),
              8 => 
              array (
                'question_id' => 9,
                'question_text' => 'Implication personnelle',
                'categorie_id' => 2,
              ),
              9 => 
              array (
                'question_id' => 10,
                'question_text' => 'Esprit d’ouverture et flexibilité',
                'categorie_id' => 2,
              ),
              10 => 
              array (
                'question_id' => 11,
                'question_text' => 'Innovation',
                'categorie_id' => 2,
              ),
              11 => 
              array (
                'question_id' => 12,
                'question_text' => 'Discrétion',
                'categorie_id' => 2,
              ),
              12 => 
              array (
                'question_id' => 13,
                'question_text' => 'Responsabilisation',
                'categorie_id' => 2,
              ),
              13 => 
              array (
                'question_id' => 14,
                'question_text' => 'Empathie',
                'categorie_id' => 2,
              ),
              14 => 
              array (
                'question_id' => 15,
                'question_text' => 'Démarche de Problem Solving et capacité à proposer des solutions',
                'categorie_id' => 3,
              ),
              15 => 
              array (
                'question_id' => 16,
                'question_text' => 'Coaching, accompagnement et développement des compétences des collaborateurs',
                'categorie_id' => 3,
              ),
              16 => 
              array (
                'question_id' => 17,
                'question_text' => 'Gestion des relations et des conflits au sein de l\'équipe',
                'categorie_id' => 3,
              ),
              17 => 
              array (
                'question_id' => 18,
                'question_text' => 'Organisation opérationnelle d\'une unité de gestion',
                'categorie_id' => 3,
              ),
              18 => 
              array (
                'question_id' => 19,
                'question_text' => 'Plan de contrôle permanent et mesures d\'atténuation des risques',
                'categorie_id' => 3,
              ),
              19 => 
              array (
                'question_id' => 20,
                'question_text' => 'Pratiques managériales et processus RH',
                'categorie_id' => 3,
              ),
              20 => 
              array (
                'question_id' => 21,
                'question_text' => 'Réalisation d\'un suivi ou d\'un bilan d\'activité et réorientation des travaux / des activités en conséquence',
                'categorie_id' => 3,
              ),
            ));
        }
        if (DB::table('roles')->doesntExist()) {
            DB::table('roles')->insert(array (
              0 => 
              array (
                'id' => 1,
                'role_name' => 'n0',
              ),
              1 => 
              array (
                'id' => 2,
                'role_name' => 'n1',
              ),
              2 => 
              array (
                'id' => 3,
                'role_name' => 'n2',
              ),
            ));
        }
    }

    public function down(): void
    {
        foreach ([
            'entretien_date', 'signature', 'quantitative_objectives', 'qualitative_objectives',
            'opportunities1', 'opportunites', 'appreciations', 'finalresult', 'evaluations',
            'user_results', 'user_progress', 'responses', 'roles', 'questions', 'categories',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
