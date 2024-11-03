<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AddressVersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

#[ORM\Entity(repositoryClass: AddressVersionRepository::class)]
#[ORM\Table(options: ['row_format' => 'DYNAMIC'])]
#[ORM\Index(columns: ['object_class'], name: 'log_class_lookup_idx')]
#[ORM\Index(columns: ['logged_at'], name: 'log_date_lookup_idx')]
#[ORM\Index(columns: ['username'], name: 'log_user_lookup_idx')]
#[ORM\Index(columns: ['object_id', 'object_class', 'version'], name: 'log_version_lookup_idx')]
class AddressVersion extends AbstractLogEntry implements VersionInterface
{
}
