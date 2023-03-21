<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AppInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install necessary application\'s data.';

    /**
     * @var \App\Repositories\RoleRepository
     */
    protected $roleRepository;

    /**
     * @param  \App\Repositories\RoleRepository  $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();

        $this->roleRepository = $roleRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $roles = [
                Role::ROLE_ADMIN   => 'Ultimate role.',
                Role::ROLE_EDITOR  => 'Editor role.',
                Role::ROLE_WRITER  => 'Writer role.',
                Role::ROLE_USER    => 'User role.',
            ];

            foreach ($roles as $name => $description) {
                $this->roleRepository->getOrCreate([
                    'name'          => $name,
                    'description'   => $description,
                ]);
            }
        });

        $this->info('All necessary application\'s data have been installed.');
    }
}
