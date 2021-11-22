  # heroku
  "post-update-cmd": [
            "@auto-scripts"
        ],
        "compile": [
            "php bin/console doctrine:migrations:migrate",
            "php bin/console doctrine:fixtures:load --no-interaction --env=PROD"
        ]
 - list all routes in APP
 php bin/console debug:router
 
 composer require --dev symfony/maker-bundle
 
 - route list :
 php bin/console debug:router
 
 - more info by routes : 
  php bin/console debug:router app_lucky_number
  ou tester une route
  php bin/console router:match /lucky/number/8
  
  - php bin/console make:migration
  pour creer un fichier de migration de la BDD 
  
  - php bin/console doctrine:migrations:migrate -> pour l'appliquer (différentiel, le fichier généra ne contient que les nv ajouts par rapport à la version précédente)

 ## Twig
  {{ var }} : sert a afficher la valeur d'une var (comme angular
 # Exemple de Foreach 
  {% if users %}
    <ul>
        {% for user in users %}
            <li>{{ user.username|e }}</li>
        {% endfor %}
    </ul>
{% endif %})

#Exemple de If
{% if online == false %}
    <p>Our website is in maintenance mode. Please, come back later.</p>
{% endif %}

## Commentaires
raccourci CTRL + / ou alors {# ............. #}

## Les Filtres
{{ 'welcome' | upper }}
lower, date, date_modify
https://twig.symfony.com/doc/2.x/filters/index.html

## Routes 
# regex routes
* @Route("/blog/{page}", name="blog_list", requirements={"page"="\d+"})
on peut spécifier une regex dans la route

#Multi routes 
On peut avoir plusieurs routes pour un endpoint
/**
     * @Route("/hello/{name}/age/{age}", name="hello_with_name")
     * @Route("/hello/{name}/{age}", name="hello_with_name")

## Creation BDD avec CLI 
#  php bin/console doctrine:database:create (voir nom bdd dans .env)

## Entity
php bin/console make:entity

## Migration 
php bin/console make:migration -> creer le fichier de migration qu'il faura lancer (voir ligne suivante)
php bin/console doctrine:migrations:migrate -> prend tout les fichiers de migration non exécutes et fait les migrations

## Fake BDD
composer require orm-fixtures --dev

Aller dans /src/DataFixtures/AppFixtures et compléter la class AppFixtures
Pour lancer le traitement pour remplir les data faire la commande suivante 
php bin/console doctrine:fixtures:load 
ou
php bin/console doctrine:fixtures:load --append

# doctrine
FindOneByChamp => renvoie un objet
FindByChamp => renvoie un tableau d'objet

## Faker
# composer require fzaninotto/faker
voir fichier DataFixtures\AppFixtures

## Slugify
composer require cocur/slugify

## path() with params
#{{path('ads_show',{'slug':ad.slug})}}
# route en question * @Route("/show/{slug}", name="ads_show")

# Variables twig par ex pour remplacer {{path('ads_show',{'slug':ad.slug})}}

## ParamConverter
# sert à convertir un paramètre d'une route en entité 
#public function show($slug, Adrepostirory $repo)
    {
        $repo = $adrepo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'controller_name' => 'AdController',
            'ad' => $ad,
        ]);

        équivaut à

#public function show($slug, Ad $ad)
    {
    // $repo = $adrepo->findOneBySlug($slug);
    return $this->render('ad/show.html.twig', [
        'controller_name' => 'AdController',
        'ad' => $ad,
    ]);

ou à

#public function show(Ad $ad)
    {
    return $this->render('ad/show.html.twig', [
        'controller_name' => 'AdController',
        'ad' => $ad,
    ]);

## FORMS 
#Cli php bin/console make:form
https://symfony.com/doc/4.4/reference/forms/types/submit.html

{# {{ form(form) }} #}
équivaut à 
{{ form_start(form) }}
{{ form_widget(form.title) }} 
<button type="submit" class="btn btn-primary">Créer la nouvelle annonce</button>
{{ form_end(form) }}

#Afficher un champ spécifique 
{{ form_widget(form.title) }} ou 
{{ form_row(form.title) }}
#Afficher le label d'un input du form
{{ form_label(form.title) }}

##{{form_row(form)}} =  
{{form_label(form)}}
{{form_errors(form)}}
{{form_widget(form)}}
{{form_help(form)}}

##FORM ENTRY COLLECTION
# Définit chaque entry/élément du sous formulaire ad_images (ImageType) (voir fichier new.html.twig)
{% block _ad_images_entry_widget %}
	<div class="form-group">
		<div class="row">
			<div class="col">
				{{ form_widget(form.url) }}
			</div>
			<div class="col">
				{{ form_widget(form.caption) }}
			</div>
		</div> 
	</div>
{% endblock %}


## FLASH
#$this->addFlash(
                'success',
                "L'annonce <strong>{ad.getTitle()} a bien été ajoutée"
            );

## Controle de saisie des forms
# Allez dans l'entity et rajouer les annotations suviantes
 /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, max=255, minMessage="Votre introduction doit faire plus de 20 caractères" )
     */
    private $introduction;
    (ne pas oublier l'import ctrl + alt +i ou clic droit import class)

#Champ unique (rajouter annotation début classe)
/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Une autre annnonce possède déjà ce titre, merci de le modifier"
 * )
 */
class Ad

## Hash Password
# public function getRoles()
# rajouter 'implements User UserInterface
# et use Symfony\Component\Security\Core\User\UserInterface;
# et Ajouter les 4 méthodes 
   public function getRoles()
    {
        return ['ROLES_USER'];
    }

    public function getPassword()
    {
        return $this->hash;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials() {
        
    }

## Login
# check security.yaml
# security:
    encoders:
    // Entité utilisée pour le login
        App\Entity\User:
            // Algorythme utilisé
            algorithm: bcrypt
            
    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        users_in_memory: { memory: null }
        in_database:
            entity: 
                // Entité utilisée pour le login
                class: App\Entity\User
                // champ qui sert à logger
                property: email
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            // rajouté dans tuto mais ???
            provider: in_database

            form_login:
                // path du login
                login_path: account_login
                check_path: account_login

            logout:
            // path du logout et target redirige vers la route une fois bien loggé
                path: account_logout
                target: account_login

### 7.12 - 

## 9.4 - auth guard
 Rajouter * @IsGranted("ROLE_USER") dans le controller qu'on veut filtrer en mettant un role spécifique
 IsGranted ou @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')")
 * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas; vous ne pouvez pas la modifier")
# https://symfony.com/doc/4.4/security/expressions.html

## Customize Errors
# 

## Custome Templates for error with Twig
# Création 
templates/bundles/TwigBundle/Exception 
Creer ficher error404.html.twig

# Si on veut tester en dev 
http://127.0.0.1:8000/_error/404

var\cache\dev

## Rappel Forms
public function book(Ad $ad): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

## 11.10
# Afficher dynamique selon un paramètre get passé en URL (pour la 1ere fois ou on réserve l'annonce)

# Url
Voici le paramètre : http://127.0.0.1:8000/booking/158?withAlert=true
# Code
{% if app.request.query.get('withAlert') %}
		<div class="container">
			<div class="alert alert-success">

				<h4 class="alert-heading">Bravo votre réservation a eu lieu avec succès</h4>
				<p>Votre réservation auprès de
					<strong>
						<a href="{{path('user_show',{'slug':author.slug})}}">{{author.fullName}}</a>
					</strong>
					pour l'annonce de
					<strong>
						<a href="{{path('ads_show',{'slug':ad.slug})}}"></a>
					</strong>
					a bien été prise en compte
				</p>
			</div>
		</div>
	{% endif %}

## 14.22 Groupe de validation
# init du form dans le controller => $form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default","front"]
        ]); 

# dans entity rajouter la contrainte à un groupe 
# add 3rd arg => , groups={"front"}

# Pour résumer on définit les groupe dans l'entity et on gère ça lors de l'appel du $form dans le controller 
# ou alors on fait ca dans le formType dans le configureOptions ce qui donne
public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => ["Default", "front"]
        ]); 
    }

## Contrainte de routing
# rajouter dans le controller une "requirements" -> 
    /**
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page" : "\d+"})
     */

# ou directement au niveau du paramètres
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
# le ? dans  <\d+>? signifie que ce paramètre est optionnel
# le 1 signifie "paramètre par défaut sinon le $page = 1 dans les paramètres du controller n'est pas pris en compte

## Recherche d'injection de dépendance
# php bin/console debug:container (service)

# $users = $manager->createQuery('SELECT u from App\Entity\User u')->getResult();
récupère les resultats sous forme d'objets Entité (ici des objects User)

# $users = $manager->createQuery('SELECT count(u) from App\Entity\User u')->getSingleScalarResult();
récupère le résultat sous forme d'une variable scalaire simple et non pas un tableau dans un tableau  ... pour voir qu'une seule valeur

# Tips lorsqu'on veut renvoyer un tableau qui a les meme clé que valeurs
'stats' => [
                'users' => $users,
                'ads' => $ads,
                'bookings' => $bookings,
                'comments' => $comments,
            ]

équivant à 
'stats' => compact('users','ads','bookings','comments')

# QueryBuilder
public function findBestAds($limit) {
        return $this->createQueryBuilder('a')
        ->select('a as annonce,AVG(c.rating) as avgRatings')
        ->join('a.comments', 'c')
        ->groupBy('a')
        ->orderBy('avgRatings','DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }


