<?php

namespace LBHurtado\ElectionRules\Domain;

use LBHurtado\ElectionRules\Support\CanonicalJson;

final readonly class ElectionRules
{
    /** @param list<ContestRule> $contests */
    public function __construct(
        public string $electionId,
        public array $contests,
        public string $scope = 'national',
        public string $schemaVersion = 'election-rules.v1',
    ) {
        if (trim($this->electionId) === '') {
            throw new \InvalidArgumentException('Election ID must not be empty.');
        }
        $codes = array_map(static fn (ContestRule $contest): string => $contest->contestCode, $contests);
        if (count($codes) !== count(array_unique($codes))) {
            throw new \InvalidArgumentException('Contest codes must be unique within an election.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'package_type' => 'election-rules',
            'schema_version' => $this->schemaVersion,
            'election_id' => $this->electionId,
            'scope' => $this->scope,
            'contests' => array_map(static fn (ContestRule $contest): array => $contest->toArray(), $this->contests),
        ];
    }

    public function hash(): string
    {
        return CanonicalJson::sha256($this->toArray());
    }
}
