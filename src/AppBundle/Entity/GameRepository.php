<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Entity\Game;

class GameRepository extends EntityRepository
{

	public function getFinishedGamesThatUserHasNotViewed(User $user)
	{
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g JOIN g.players p JOIN p.user u WHERE u.id =:userId AND g.gameState =:state AND p.viewedGame = false');
		$query->setParameter('userId', $user->getId());
		$query->setParameter('state', Game::STATE_FINISHED);
		$result = $query->getResult();
		return $result;
	}
	
	public function getUserLiveGames(User $user)
	{
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g JOIN g.players p JOIN p.user u WHERE u.id =:userId AND g.gameState =:state');
		$query->setParameter('userId', $user->getId());
		$query->setParameter('state', Game::STATE_HAS_PLAYERS_BUT_NOT_READY);
		$result = $query->getResult();
		return $result;
	}
	
	public function getUserFinishedGames(User $user)
	{
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g JOIN g.players p JOIN p.user u WHERE u.id =:userId AND g.gameState =:state');
		$query->setParameter('userId', $user->getId());
		$query->setParameter('state', Game::STATE_FINISHED);
		$result = $query->getResult();
		return $result;
	}
	
	public function removeUserFromGame(User $user, Game $game)
	{
		if($game->isUserInGame($user)){
			$em = $this->getEntityManager();
	    	$player = $game->getPlayerByUserId($user->getId());
	    	// note it is important that we remove the player from the game before deleting them so that game state is updated to reflect this change
	    	$game->removePlayer($player);
	    	$em->remove($player);
	    	$em->flush();
		}
	}
	
	public function getAllLiveGames()
	{
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g WHERE (g.gameState !=:state) OR (g.gameState = :state AND g.finishedAt > :expiryDate)');
		$query->setParameter('state', Game::STATE_FINISHED);
		$query->setParameter('expiryDate', new \DateTime('-2 minutes'));
		$result = $query->getResult();
		return $result;
	}

	public function getGameNamesInUse()
	{
		$query = $this->getEntityManager()->createQuery('SELECT g.name FROM AppBundle:Game g WHERE (g.gameState !=:state) OR (g.gameState = :state AND g.finishedAt > :expiryDate)');
		$query->setParameter('state', Game::STATE_FINISHED);
		$query->setParameter('expiryDate', new \DateTime('-2 minutes'));
		$result = $query->getScalarResult();
		// doctrine returns an array of arrays. Each name is wrapped in it's own array. We want a single array with names/strings as array elements
		// use array_map to convert our array of arrays to a single array
		$names = array_map('current', $result);
		return $names;
	}
	
	public function isNameInUse($name)
	{
		$name = (string) $name;
		$query = $this->getEntityManager()->createQuery('SELECT COUNT(g.id) FROM AppBundle:Game g WHERE g.gameState !=:state AND g.name =:name');
		$query->setParameter('state', Game::STATE_FINISHED);
		$query->setParameter('name', $name);
		$result = $query->getSingleScalarResult();
		return (boolean) $result;
	}
}