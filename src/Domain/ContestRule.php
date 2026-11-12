<?php

namespace LBHurtado\ElectionRules\Domain;

final readonly class ContestRule
{
    /** @param list<string> $candidateCodes */
    public function __construct(
        public string $contestCode,
        public string $title,
        public int $maximumSelections,
        public array $candidateCodes = [],
        public string $scope = 'national',
        public int $displayOrder = 0,
    ) {
        if (trim($this->contestCode) === '' || trim($this->title) === '') {
            throw new \InvalidArgumentException('Contest code and title must not be empty.');
        }
        if ($this->maximumSelections < 0 || $this->maximumSelections > count($this->candidateCodes)) {
            throw new \InvalidArgumentException('Contest selection limit is invalid.');
        }
        if (count($this->candidateCodes) !== count(array_unique($this->candidateCodes))) {
            throw new \InvalidArgumentException('Contest candidate codes must be unique.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'contest_code' => $this->contestCode,
            'title' => $this->title,
            'maximum_selections' => $this->maximumSelections,
            'candidate_codes' => $this->candidateCodes,
            'scope' => $this->scope,
            'display_order' => $this->displayOrder,
        ];
    }
}
