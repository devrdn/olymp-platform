<?php

use App\Models\Contest;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
   $trail->push(__('Home'), route('home'));
});

Breadcrumbs::for('contest.index', function (BreadcrumbTrail $trail) {
   $trail->parent('home');
   $trail->push(__('Contest'), route('contest.index'));
});

Breadcrumbs::for('contest.show', function (BreadcrumbTrail $trail, string $id) {
   $trail->parent('contest.index');
   $trail->push($id, route('contest.show', $id));
});

Breadcrumbs::for('contest.create', function (BreadcrumbTrail $trail) {
   $trail->parent('contest.index');
   $trail->push(__("Create"), route('contest.create'));
});

Breadcrumbs::for('contest.edit', function (BreadcrumbTrail $trail, string $id) {
   $trail->parent('contest.show', $id);
   $trail->push(__("Edit"), route('contest.edit', $id));
});
