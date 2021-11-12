<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \App\Context\TemplateValidator;
use App\Context\QuoteTemplateManager;
use App\Context\UserTemplateManager;
use \App\Entity\Template;
use \App\Entity\Quote;
use \App\TemplateManager;

$quoteTemplateManager = new QuoteTemplateManager();
$userTemplateManager = new UserTemplateManager();
$templateValidator = new TemplateValidator();

$faker = \Faker\Factory::create();

$template = new Template(
    1,
    'Votre livraison à [quote:destination_name]',
    "
Bonjour [user:first_name],

Merci de nous avoir contacté pour votre livraison à [quote:destination_name].

Bien cordialement,

L'équipe Convelio.com
");
$templateManager = new TemplateManager(
    $quoteTemplateManager,
    $userTemplateManager,
    $templateValidator
);

$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->getSubject() . "\n" . $message->getContent();
