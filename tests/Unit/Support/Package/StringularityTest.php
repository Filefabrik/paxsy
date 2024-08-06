<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Support\Stringularity;

it(
	'missing vendor and package name',
	function() {
		new Stringularity();
	}
)->throws(UnexpectedValueException::class, 'Name and ClassName name cannot be null. Set name or/and className.');

// Shaking 4 variants they must be resulting the same.
it(
	'calc Class-String from name',
	function() {
		$s = new Stringularity('my name ');

		expect($s->toName())
			->toBe('my-name')
			->and($s->toClass())
			->toBe('MyName')
		;
	}
);

it(
	'calc Class-String from Class-String',
	function() {
		$s = new Stringularity(' MyName ');

		expect($s->toName())
			->toBe('my-name')
			->and($s->toClass())
			->toBe('MyName')
		;
	}
);

it(
	'calc Name-String from string',
	function() {
		$s = new Stringularity(className: 'my name ');

		expect($s->toName())
			->toBe('my-name')
			->and($s->toClass())
			->toBe('MyName')
		;
	}
);

it(
	'calc Name-String from Class',
	function() {
		$s = new Stringularity(className: 'MyName ');

		expect($s->toName())
			->toBe('my-name')
			->and($s->toClass())
			->toBe('MyName')
		;
	}
);
