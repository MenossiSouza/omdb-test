<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MovieImporterService;

class ImportMoviesCommand extends Command
{
    protected $signature = 'movies:import {search}';
    protected $description = 'Importa filmes da OMDb API';
    
    protected $movieImporter;

    public function __construct(MovieImporterService $movieImporter)
    {
        parent::__construct();
        $this->movieImporter = $movieImporter;
    }

    public function handle()
    {
        $search = $this->argument('search');

        $msg = $this->movieImporter->import($search);

        $this->info($msg);
    }
}
