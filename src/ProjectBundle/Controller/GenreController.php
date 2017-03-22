<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Genre;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends DefaultController
{

	// Cette méthode n'est accessible que pour nous, pour remplir notre base

	/**
	 * @Route("/create-genres")
	 */
	public function addGenresAction(Request $request)
	{
		$genres = array("Rock", "Pop", "Alternative", "Electro", "Techno", "Funk" );

		if ($request->getMethod() == 'GET') {

			$em = $this->getDoctrine()->getManager();

			// now if already exist
			$actualGenres = $this->getDoctrine()->getRepository("ProjectBundle:Genre")->findAll();

			if(count($actualGenres) == 0) {

				foreach ( $actualGenres as $genreToRemove ) {
					$em->remove( $genreToRemove );
				}

				$em->flush();

				// now add genres
				foreach ( $genres as $genre ) {

					$genreInst = new Genre();
					$genreInst->setName( $genre );

					$em->persist( $genreInst );
				}

				$em->flush();

				$response = new Response(
					'Push des genres fait avec succés !',
					Response::HTTP_OK,
					array( 'content-type' => 'text/html' )
				);

				$response->prepare( $request );
				$response->send();

			}

		}
	}

}