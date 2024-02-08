<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admin' => [[['_route' => 'admin', '_controller' => 'App\\Controller\\Admin\\DashboardController::index'], null, null, null, false, false, null]],
        '/api/adherent' => [
            [['_route' => 'app_api_adherent', '_controller' => 'App\\Controller\\Api\\AdherentController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_adherent_create', '_controller' => 'App\\Controller\\Api\\AdherentController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/auteur' => [
            [['_route' => 'app_api_auteur', '_controller' => 'App\\Controller\\Api\\AuteurController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_auteur_post', '_controller' => 'App\\Controller\\Api\\AuteurController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/categorie' => [
            [['_route' => 'app_api_categorie', '_controller' => 'App\\Controller\\Api\\CategorieController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_categorie_create', '_controller' => 'App\\Controller\\Api\\CategorieController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/emprunt' => [
            [['_route' => 'app_api_emprunt', '_controller' => 'App\\Controller\\Api\\EmpruntController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_emprunt_create', '_controller' => 'App\\Controller\\Api\\EmpruntController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/livre' => [
            [['_route' => 'app_api_livre', '_controller' => 'App\\Controller\\Api\\LivreController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_livre_create', '_controller' => 'App\\Controller\\Api\\LivreController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/reservations' => [
            [['_route' => 'app_api_reservations', '_controller' => 'App\\Controller\\Api\\ReservationsController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_reservations_create', '_controller' => 'App\\Controller\\Api\\ReservationsController::create'], null, ['POST' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api(?'
                    .'|/\\.well\\-known/genid/([^/]++)(*:43)'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:78)'
                    .'|/(?'
                        .'|docs(?:\\.([^/]++))?(*:108)'
                        .'|c(?'
                            .'|ontexts/([^.]+)(?:\\.(jsonld))?(*:150)'
                            .'|ategorie/(\\d+)(?'
                                .'|(*:175)'
                            .')'
                        .')'
                        .'|e(?'
                            .'|rrors/([^/]++)(?'
                                .'|(*:206)'
                            .')'
                            .'|mprunt/(?'
                                .'|(\\d+)(*:230)'
                                .'|([^/]++)(?'
                                    .'|(*:249)'
                                .')'
                            .')'
                        .')'
                        .'|validation_errors/([^/]++)(?'
                            .'|(*:289)'
                        .')'
                        .'|a(?'
                            .'|dherent/(\\d+)(?'
                                .'|(*:318)'
                            .')'
                            .'|uteur/(\\d+)(?'
                                .'|(*:341)'
                            .')'
                        .')'
                        .'|livre/(\\d+)(?'
                            .'|(*:365)'
                        .')'
                        .'|reservations/(\\d+)(?'
                            .'|(*:395)'
                        .')'
                    .')'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:434)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        43 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => 'true'], ['id'], null, null, false, true, null]],
        78 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], null, null, false, true, null]],
        108 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], null, null, false, true, null]],
        150 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], null, null, false, true, null]],
        175 => [
            [['_route' => 'app_api_categorie_show', '_controller' => 'App\\Controller\\Api\\CategorieController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_categorie_update', '_controller' => 'App\\Controller\\Api\\CategorieController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_categorie_delete', '_controller' => 'App\\Controller\\Api\\CategorieController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        206 => [
            [['_route' => '_api_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors_problem'], ['status'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors_hydra'], ['status'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors_jsonapi'], ['status'], ['GET' => 0], null, false, true, null],
        ],
        230 => [[['_route' => 'app_api_emprunt_get', '_controller' => 'App\\Controller\\Api\\EmpruntController::getById'], ['id'], ['GET' => 0], null, false, true, null]],
        249 => [
            [['_route' => 'app_api_emprunt_update', '_controller' => 'App\\Controller\\Api\\EmpruntController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_emprunt_delete', '_controller' => 'App\\Controller\\Api\\EmpruntController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        289 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Symfony\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Symfony\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Symfony\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi'], ['id'], ['GET' => 0], null, false, true, null],
        ],
        318 => [
            [['_route' => 'app_api_adherent_show', '_controller' => 'App\\Controller\\Api\\AdherentController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_adherent_update', '_controller' => 'App\\Controller\\Api\\AdherentController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_adherent_delete', '_controller' => 'App\\Controller\\Api\\AdherentController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        341 => [
            [['_route' => 'app_api_auteur_show', '_controller' => 'App\\Controller\\Api\\AuteurController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_auteur_delete', '_controller' => 'App\\Controller\\Api\\AuteurController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'app_api_auteur_update', '_controller' => 'App\\Controller\\Api\\AuteurController::update'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        365 => [
            [['_route' => 'app_api_livre_show', '_controller' => 'App\\Controller\\Api\\LivreController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_livre_update', '_controller' => 'App\\Controller\\Api\\LivreController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_livre_delete', '_controller' => 'App\\Controller\\Api\\LivreController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        395 => [
            [['_route' => 'app_api_reservations_get', '_controller' => 'App\\Controller\\Api\\ReservationsController::getById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_reservations_update', '_controller' => 'App\\Controller\\Api\\ReservationsController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_reservations_delete', '_controller' => 'App\\Controller\\Api\\ReservationsController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        434 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
