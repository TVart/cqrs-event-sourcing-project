# Kata Test N° 1
Cas d'école pour comprendre les patterns d'architecture CQRS et Event Sourcing.

## Introduction
    Ce projet est inspiré du talk de @fpellet et @Ouarzy disponible ici https://www.youtube.com/watch?v=S1V4t7SXXCU&ab_channel=DevoxxFR
    L'objectif est de comprendre les principes pour se lancer dans l'échaffaudage d'un projet Event sourcing / CQRS
    
    Dans un projet en clean code, les règles métiers qu'on peut appeler commandes ou usecase sont séparé du domaine et de l'infrastructure, et 
    sont placées dans un dossier communément nommé Application. 
    Ainsi, dans une application CQRS (Command Query Responsability Segregation) on distingue un deuxième niveau de ségrégation. 
    Cette dernière se base sur le principe de single responsability des typologies de commandes. D'une part nous avons donc les Commandes.
    Ce sont toutes les opérations d'écriture, qui déclanche une mutation de l'état. Mais au lieu de persister la modification au nieau de 
    l'agrégart, on stock l'évenement à son origine dans un store ou un stream, à partir duquel on déduit le nouvel état de l'aggrégat.
    De l'autre coté nous avons les Query, qui sont les opération de lecture et de visulaisation de l'aggrégat. Pour afficher l'aggregat on se base sur les
    projections qui ont été faite par les évenements.
    
    Ce qui est spécifique aux application de type CQRS c'est qu'il n'y pas de notion d'état stocké, et comme il n'y a pas d'état, il n'y a pas non plus
    d'opération de mise à jour d'état.
    
    Toute est articulé au sujet d'aggrégat, et d'historicisation des différents états de chaque instance d'aggregat. L'état final de l'aggrégat étant calculé
    par les différents évenements qui sont stokés soit dans un event store, soit dans un event stream. 

## Message
    L'objectif de l'application qui est construite dans ce projet est celle d'un clone de Twitter.  

## EventStream / History