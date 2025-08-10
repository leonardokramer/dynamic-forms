<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um usuário administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Nome do administrador');
        $email = $this->ask('Email do administrador');
        $password = $this->secret('Senha do administrador');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'isAdmin' => true,
            ]);
            $this->info('Usuário atualizado para administrador com sucesso!');
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'isAdmin' => true,
            ]);
            $this->info('Usuário administrador criado com sucesso!');
        }

        
    }
}
