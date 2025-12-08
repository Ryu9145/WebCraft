    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50);
                $table->string('email', 50)->unique();
                $table->string('password');
                
                // KEMBALI KE CARA SIMPEL (Satu Kolom Saja)
                // Tanpa tabel roles, langsung tulis opsinya di sini
                $table->enum('role', ['user', 'admin', 'superadmin'])->default('user');
                
                $table->enum('status', ['active', 'suspended'])->default('active');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('users');
        }
    }
