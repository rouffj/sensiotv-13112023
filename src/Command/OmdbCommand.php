<?php

namespace App\Command;

use App\Omdb\OmdbApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:omdb',
    description: 'Add a short description for your command',
)]
class OmdbCommand extends Command
{
    //private OmdbApi $omdbApi;

    public function __construct(private OmdbApi $omdbApi)
    {
        //$this->omdbApi = $omdbApi;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('keyword', InputArgument::OPTIONAL, 'Keyword for your movie search.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $keyword = $input->getArgument('keyword');

        if (!$keyword) {
            $keyword = $io->ask('Please provide a movie keyword?', 'La vérité');
        }

        $movies = $this->omdbApi->requestAllBySearch($keyword);
        $io->title(sprintf('Searching movie with keyword "%s"', $keyword));
        $rows = [];

        $io->progressStart(10);
        foreach ($movies['Search'] as $movie) {
            $io->progressAdvance(1);
            usleep(50000);
            $rows[] = [$movie['Title'], $movie['Year'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/'];
        }
        $io->progressFinish();
        $io->write("\n\r");

        $io->table(['Title', 'Year', 'Link'], $rows);

        //dump($keyword, $movies);

        $io->success('10/'.$movies['totalResults'].' movies displayed. ');

        return Command::SUCCESS;
    }
}
