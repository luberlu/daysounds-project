<?php

namespace ProjectBundle\Repository;

/**
 * PlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends \Doctrine\ORM\EntityRepository
{
	public function listAndTestLinkPlayers($entryInput)
	{
		if (filter_var($entryInput, FILTER_VALIDATE_URL) === FALSE) {
			return null;
		}

		$listOfPlayers = $this->findAll();

		foreach($listOfPlayers as $player){

			$playerLinkExample = $player->getlinkExample();

			if($this->testBetweenToLinks($playerLinkExample, $entryInput))
				return array('type' => $player->getId(), 'name' =>$player->getName());

		}

		return null;
	}

	private function testBetweenToLinks($databaseLink, $entryLink){

		$entry = parse_url($entryLink);
		$entry = preg_replace('#^www\.(.+\.)#i', '$1',  $entry["host"]);

		if($databaseLink == $entry){
			return true;
		}

		return false;

	}

	// prefer domain name method
//	private function testBetweenToLinks($databaseLink, $entryLink){
//
//		similar_text($databaseLink, $entryLink, $percent);
//
//		if($percent > 30){
//			return true;
//		}
//
//		return false;
//
//	}
}
