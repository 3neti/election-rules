# `3neti/election-rules`

Framework-neutral election, office, and contest-rule primitives for the WAES
EMS. The package expresses the approved rules that determine which contests
and candidate codes may appear in a ballot style.

## Responsibility

This package owns election identity and scope, contest codes and titles,
candidate membership by contest, maximum selection limits, contest ordering,
and deterministic rule-set serialization and hashing.

It does not own candidate identity or CLC parsing, precinct topology or POP
parsing, ballot layout or OBT generation, legal approval authority, device
assignment, custody, or EMS user-interface workflows. Legal and COMELEC-specific
interpretations remain approved inputs to the EMS; this package makes those
decisions explicit and reproducible.

## Domain Model

`ContestRule` defines a contest, its display title, selection ceiling,
candidate-code membership, scope, and display order. It rejects duplicate
candidate codes and a maximum greater than the candidate count.

`ElectionRules` is an election-scoped collection and contest codes must be
unique within it:

```json
{
  "package_type": "election-rules",
  "schema_version": "election-rules.v1",
  "election_id": "2025NLE",
  "scope": "national",
  "contests": []
}
```

## Determinism and Hashing

All public value objects expose `toArray()`. Rule-set hashes are SHA-256 hashes
of the canonical JSON representation produced by the package. The EMS should
persist the rule-set hash in every resolved ballot style, OBT manifest, and
precinct package that depends on these rules.

Changing a contest limit, candidate membership, scope, or ordering produces a
new rule-set hash and must be treated as a new compilation input.

## Example

```php
use LBHurtado\ElectionRules\Domain\ContestRule;
use LBHurtado\ElectionRules\Domain\ElectionRules;

$rules = new ElectionRules('2025NLE', [
    new ContestRule('SENATOR', 'Senator', 12,
        ['SEN-001', 'SEN-002'], 'national', 10),
]);

$rulesHash = $rules->hash();
```

Candidate codes are references to the candidate registry. This package does
not duplicate candidate names or decide whether a candidate is legally
eligible.

## EMS Relationship

```text
approved election rules
    + candidate registry
    + project of precincts
    -> ballot-style resolver
    -> OBT compiler
```

`3neti/ballot-pdf` later consumes the resolved contest definitions and mapping
hash for ballot sessions and Truth QR verification.

## Current Status

This is a domain scaffold with immutable rules, validation, canonical hashing,
and focused tests. Office catalogs, jurisdiction applicability rules, approval
workflows, and source-specific rule importers remain EMS work.

## Development

```bash
composer install
vendor/bin/pest
```

The package is PHP 8.4+, framework-neutral, and uses no external runtime
dependencies.
