Contribute
========

## > Semantic Versioning
Nous utiliserons le principe de semantic versioning (SemVer) pour définir les versions de l’application.
Les principes généraux sont :

MAJOR version : pour les changements qui rendent incompatibles l’API
MINOR version : pour l’ajout de fonctionnalité qui conserve la retro-compatibilité
PATCH version : pour la correction de bugs qui conserve la retro-compatibilité

La nomenclature est donc MAJOR.MINOR.PATCH (ex: 1.0.2).
Pour plus d’informations : https://semver.org/

## > Issue and Pull Request for project insiders

* 1- Issue: créer l’issue, spécifier le type d’issue (bug fix, ajout de fonctionnalité, etc..)
* 2- Create Local branch : créer une branche locale avec le numéro de version en accord avec le SemVer
* 3- Commit changes : créer un ou plusieurs commits en relation avec les différentes modification engendrées par l’issue
* 4- Pull request: une fois les modifications effectuées, créer une pull request en relation avec l’issue concernée
* 5- Travis CI + Codacy: l’intégration continue effectue les tests et donne son rapport
* 6- Merge branch: la branche peut-être mergée avec la branche principale si les tests et les reviews sont ok
