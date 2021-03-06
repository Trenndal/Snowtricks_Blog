<?php

namespace Trenndal\SnowtricksBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * EditTrickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EditTrickRepository extends \Doctrine\ORM\EntityRepository
{
	public function getTricks($page = 1, $trickPerPage = 10)
    {
        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$page must be an integer ('.gettype($page).' : '.$page.')'
            );
        }

        if(!is_numeric($trickPerPage)) {
            throw new \InvalidArgumentException(
                '$trickPerPage must be an integer ('.gettype($trickPerPage).' : '.$trickPerPage.')'
            );
        }

        $dql = $this->createQueryBuilder('a');
        $dql->leftJoin('a.images', 'b')->addSelect('b');
        $dql->orderBy('a.date', 'DESC');

        $firstResult = ($page - 1) * $trickPerPage;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($trickPerPage);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }
}
