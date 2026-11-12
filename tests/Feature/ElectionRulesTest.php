<?php

use LBHurtado\ElectionRules\Domain\ContestRule;
use LBHurtado\ElectionRules\Domain\ElectionRules;

it('creates deterministic election rules', function (): void {
    $rules = new ElectionRules('2025NLE', [
        new ContestRule('SENATOR', 'Senator', 2, ['SEN-001', 'SEN-002', 'SEN-003']),
    ]);

    expect($rules->toArray()['package_type'])->toBe('election-rules')
        ->and($rules->hash())->toHaveLength(64);
});

it('rejects a selection limit greater than the candidate count', function (): void {
    expect(fn (): ContestRule => new ContestRule('SENATOR', 'Senator', 3, ['SEN-001', 'SEN-002']))
        ->toThrow(InvalidArgumentException::class);
});
