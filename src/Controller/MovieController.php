<?php

namespace App\Controller;

use App\Omdb\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('/{id}', name: 'movie_show', requirements: ['id' => '\d+'])]
    public function show($id): Response
    {
        return $this->render('movie/show.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/latest', name: 'movie_latest')]
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }

    #[Route('/search', name: 'movie_search')]
    public function search(Request $request, OmdbApi $omdbApi): Response
    {
        $keyword = $request->query->get('keyword', 'Harry Potter');
        //$movies = file_get_contents('http://www.omdbapi.com/?apikey=28c5b7b1&s=Blue');
        #$omdbApi = new OmdbApi($httpClient, '28c5b7b1', 'https://www.omdbapi.com', '');
        $movies = $omdbApi->requestAllBySearch($keyword);
        dump($keyword, $movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies
        ]);
    }
}
