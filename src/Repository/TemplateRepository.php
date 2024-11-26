<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Template;
use App\Entity\Tag;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 *
 * @method Post|null findOneByTitle(string $postTitle)
 *
 * @template-extends ServiceEntityRepository<Template>
 */
class TemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function returnAllTemplates(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }

    /**
     * @return Template[]
     */
    // public function findBySearchQuery(string $query, int $limit = Paginator::PAGE_SIZE): array
    // {
    //     $searchTerms = $this->extractSearchTerms($query);

    //     if (0 === \count($searchTerms)) {
    //         return [];
    //     }

    //     $queryBuilder = $this->createQueryBuilder('p');

    //     foreach ($searchTerms as $key => $term) {
    //         $queryBuilder
    //             ->orWhere('p.title LIKE :t_'.$key)
    //             ->setParameter('t_'.$key, '%'.$term.'%')
    //         ;
    //     }

    //     /** @var Template[] $result */
    //     $result = $queryBuilder
    //         ->orderBy('p.publishedAt', 'DESC')
    //         ->setMaxResults($limit)
    //         ->getQuery()
    //         ->getResult()
    //     ;

    //     return $result;
    // }

    /**
     * Transforms the search string into an array of search terms.
     *
     * @return string[]
     */
    // private function extractSearchTerms(string $searchQuery): array
    // {
    //     $terms = array_unique(u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim()->split(' '));

    //     // ignore the search terms that are too short
    //     return array_filter($terms, static function ($term) {
    //         return 2 <= $term->length();
    //     });
    // }
}
