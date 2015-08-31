<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Entity\Game;

class GameRepository extends EntityRepository
{

	public function getFinishedGamesThatUserHasNotViewed(User $user){
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g JOIN g.players p JOIN p.user u WHERE u.id =:userId AND g.gameState =:state AND p.viewedGame = false');
		$query->setParameter('userId', $user->getId());
		$query->setParameter('state', Game::STATE_FINISHED);
		$result = $query->getResult();
		return $result;
	}
	
	public function getUserLiveGames(User $user){
		$query = $this->getEntityManager()->createQuery('SELECT g FROM AppBundle:Game g JOIN g.players p JOIN p.user u WHERE u.id =:userId AND g.gameState =:state');
		$query->setParameter('userId', $user->getId());
		$query->setParameter('state', Game::STATE_HAS_PLAYERS_BUT_NOT_READY);
		$result = $query->getResult();
		return $result;
	}
}