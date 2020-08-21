<?php
    class FunfactController extends Controller
    {

        public function __construct($request)
        {
            parent::__construct($request);
        }
        function post()
        {
            InternalError::$code = 404;
            InternalError::$message = 'Not Found';
            InternalError::display();
        }

        function get()
        {
            // source: https://factslegend.org/20-facts-burgers/
            $facts = [
                "\"Burger\" est en fait un nom abrégé. Le nom actuel est Hamburger. Le nom Hamburger est dérivé des steaks de Hambourg qui ont été introduits aux États-Unis par des immigrants allemands.",
                "Les hamburgers n'étaient pas vraiment populaires avant leur introduction à l'exposition universelle de St. Louis en 1904.",
                "Rien qu'en Amérique, 50 milliards de hamburgers sont consommés en une seule année ! C'est un sacré chiffre !",
                "Au Wisconsin's Seymour, il y a quelque chose connu sous le nom de Hamburger Hall of Fame !",
                "Les hamburgers et les cheeseburgers représentent 71% du bœuf servi dans les hôtels commerciaux des États-Unis.",
                "Si tous les hamburgers consommés par les Américains au cours d'une année étaient disposés en ligne droite, cela ferait 32 fois ou plus le tour de notre Terre !",
                "De nombreuses villes américaines prétendent avoir inventé cet aliment intéressant, mais on pense généralement qu'il a été inventé en 1900 à New Haven, dans le Connecticut.",
                "En 1921, le premier fast-food a ouvert ses portes et vendait des hamburgers pour seulement 5 cents !",
                "60% des sandwiches vendus dans le monde sont en fait des hamburgers.",
                "McDonald's détient le record de vente de 300 milliards de hamburgers à ce jour. La société vend 75 hamburgers ou plus par seconde.",
                "À Clearfield, en Pennsylvanie, il y a un pub connu sous le nom de Denny's Beer Barrel Pub. En 2007, le pub a mis en vente un hamburger de 55kg.",
                "Les hamburgers sont souvent connus sous le nom de Liberty Sandwich. Ce nom a été introduit par les soldats américains pendant la première guerre mondiale parce qu'ils voulaient éviter tout nom allemand.",
                "Ne pensez jamais à la santé quand vous mangez des hamburgers. Les experts en diététique disent qu'une portion de viande doit peser près de 85g, soit à peu près l'épaisseur d'un jeu de cartes.",
                "Dans le Nevada, à Las Vegas, il y a un restaurant qui propose ce que son propriétaire appelle de la \"pornographie alimentaire\".",
                "Sonya Thomas détient le record du monde de manger un Big Daddy Cheeseburger pesant 9kg en exactement 27 minutes.",
                "La personne qui les a inventé est Louis Lassen qui, pour la première fois au café Louis Lunch, a offert un sandwich au bœuf haché à un ouvrier. C'était le premier hamburger connu.",
                "Les cheeseburgers de la Kfêt réduisent votre durée de vie de 10%"
            ];
            echo json_encode(array(
                "text" => $facts[rand(0, count($facts) - 1)]
            ));
        }

        function delete()
        {
            # Not implemented...
            InternalError::display();
        }

        function put()
        {
            # Not implemented...
            InternalError::display();
        }
    }