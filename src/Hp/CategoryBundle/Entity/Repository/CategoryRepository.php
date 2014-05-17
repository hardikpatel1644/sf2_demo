<?php

namespace Hp\CategoryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoryRepository
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */
class CategoryRepository  extends EntityRepository{

    public function findAllOrderedByName()
    {

        $queryBuilder = $this->createQueryBuilder('c')
                ->orderBy('c.categoryName', 'DESC');
        return $result = $queryBuilder->getQuery()->getArrayResult();
        
    }

}
