<?php
// ------------------------------------------------------------------------------------
// Translation for PostNuke Pagesetter module
// Translation by: Jorn Lind-Nielsen
// Traduit par : Postnuke-france
// ------------------------------------------------------------------------------------
require_once 'modules/pagesetter/guppy/lang/fra/global.php';
require_once 'modules/pagesetter/pnlang/fra/common.php';

define('_PGPUBTYPEEDITADDSTEP1', 'Etape 1: Définir un Nom et une Description');
define('_PGPUBTYPEEDITADDSTEP2', 'Etape 2: Définir les Champs de publication.');
define('_PGPUBTYPEEDITADDSTEP2NOTE', 'Cliquer sur [+] pour ajouter un nouvel enregistrement sous l\enregistrement courant.  Repéter l\opération tant que nécessaire.  Modification possible ensuite depuis le menu principal : Publications -> Types');
define('_PGBACKTOADMIN', 'Aller à l\'administration');
define('_PGBTBACKTOPUBLIST', 'Aller aux publications');
define('_PGBTTYPEEXTRA', 'Cliquer pour ajouter des paramètres complémentaires pour ce type d\'enregistrement');
define('_PGCANCEL', 'Annuler'); //
define('_PGCOMMIT', 'Exécuter');
define('_PGCONFIGURATION', 'Configuration de Pagesetter');
define('_PGCONFIGAUTOFILLPUBDATE', 'Publication de la date automatique');
define('_PGCONFIGEDITOR', 'Editeur');
define('_PGCONFIGEDITORENABLED', 'Activer l\'utilisation de l\'éditeur htmlArea');
define('_PGCONFIGEDITORSTYLED', 'Utiliser les styles de thème dans l\'éditeur');
define('_PGCONFIGEDITORUNDO', 'Activer annuler dans l\'éditeur (removes statusbar)');
define('_PGCONFIGEDITORWORDKILL', 'Destruction du code Word sur coller dans l\'éditeur');
define('_PGCONFIGGENERAL', 'Géneral');
define('_PGCONFIGUPLOAD', 'Télécharger Configuration');
define('_PGCONFIRMLISTDELETE', 'Etes vous ABSOLUMENT sur de vouloir effacer cette liste?');
define('_PGCONFIRMPUBTYPEDELETE', 'Etes vous ABSOLUMENT sur de vouloir effacer cette liste?');
define('_PGCREATEDDATE', 'Créé le');
define('_PGCREATETEMPLATES', 'Sélection des templates pour auto-génération');
define('_PGDEFAULTFOLDER', 'Dossier par defaut'); //
define('_PGDEFAULTPUBTYPE', 'Publication par Défault(affichée en page d\'accueil)');
define('_PGDEFAULTSUBFOLDER', 'Sous-dossier par defaut');
define('_PGDESCRIPTION','Description');
define('_PGDOWNLOADINGSCHEMA', 'Le schéma XML Pagesetter est maintenant téléchargé. Si rien ne se produit après que quelques secondes cliquez alors sur ce lien');
define('_PGDOWNLOAD', 'Télécharger');
define('_PGEDIT', 'Modifier');
define('_PGNERROROTACCESSIBLEDIR', 'Le dossier spécifié n\'est pas accessible en écriture!');
define('_PGERRORUPLOAD', 'Erreur lors du téléchargement du fichier: ');
define('_PGERRORUPLOADDIREMPTY', 'Le dossier provisoire de téléchargement n\'a pas été placé. Svp spécifié dans l\'admin : pagesetter : configuration : general.');
define('_PGERRORUPLOADMOVE', 'Incapable de déplacer le fichier téléchargé pour/de: ');
define('_PGENABLEHOOKS', 'Activer PN-Hooks');
define('_PGENABLEREVISIONS', 'Activer contrôle de révision');
define('_PGENABLEEDITOWN', 'Permettez l\'édition de votre propre publication.');
define('_PGENABLETOPICACCESS', "Permettre l'accès au contrôle du sujet");
define('_PGEXPORT', 'Exporter');
define('_PGEXPORTFORM', 'Exporter les données de PageSetter');
define('_PGEXPORTSCHEMA', 'Exporter tous les schémas de Base de Donnée');
define('_PGFIELDTYPE','Type');
define('_PGFIELDISTITLE', 'Champ de titre');
define('_PGFOLDERNOTINSTALLED', "Le module Folder n'est pas installé"); //
define('_PGFOLDERNONE', 'Ne pas utiliser les fichiers'); //
define('_PGFOLDERSETUP','Installation pour utiliser le module Folder'); //
define('_PGFOLDERDEFAULT','Dossier par defaut'); //
define('_PGFOLDERDEFAULTTOPIC','Sujet par defaut'); //
define('_PGFOLDERSUBDEFAULT','Sous-dossier par defaut'); //
define('_PGFOLDERSTRANSFERED', 'Toutes les publications ont été transférées dans le module Folder'); //
define('_PGFTCREATEDDATE', 'Créé le');
define('_PGFTMANDATORY', 'M');
define('_PGFTMULTIPLEPAGES', 'Pages Multiples');
define('_PGFTPUBLISHDATE', 'Date de publication');
define('_PGFTPUBTITLE', 'Titre');
define('_PGFTSEARCHABLE', 'Option de Recherche');
define('_PGID','id');
define('_PGIMPORTPUBLICATIONS', 'Importer les Publications');
define('_PGIMPORTCE', 'Importer ContentExpress');
define('_PGIMPORTCEDESC', 'Créer une nouvelle publication type nommée CE et importer tous les items ContentExpress.');
define('_PGIMPORTFILEUPLOAD', 'Créer FileUpload');
define('_PGIMPORTFILEUPLOADDESC', 'Crée un type de nouvelle publication de type téléchargements de fichiers.  Ce type a été conçu pour fonctionner avec le module Folder');
define('_PGIMPORTIMAGE', 'Créer l\'image');
define('_PGIMPORTIMAGEDESC', 'Crée un type de nouvelle publication qui manipule des images.  Le type a été conçu pour fonctionner avec le module Folder');
define('_PGIMPORTNEWS', 'Importer les Nouvelles');
define('_PGIMPORTNEWSDESC', 'Créer une nouvelle publication type nommée PN-News et importer tous les items PostNuke News.');
define('_PGIMPORTNEWSEXTRA', 'Ajouter dossier image');
define('_PGIMPORTNOTE', 'Créer une Note');
define('_PGIMPORTNOTEDESC', 'Crée un nouveautype de publication pour gérer des notes. Ce type a été créé pour être utilisé avec le module "Folder"');
define('_PGIMPORTPC', 'Importe de PostCalendar');
define('_PGIMPORTPCDESC', 'Créer un type de publication nommé PostCalendar et importer les champs postcalendar.');
define('_PGIMPORTXMLSCHEMA', 'Importer schéma XML');
define('_PGIMPORTXMLSCHEMADESC', 'Créer une nouvelle publication type basé sur le fichier Pagesetter téléchargé par schéma XML.');
define('_PGIMPORTXMLSCHEMAFILE', 'Fichier XML schéma');
define('_PGINCLUDECAT', 'Inclure les catégories');
define('_PGLISTAUTHORHELP', 'C\'est ici que vous pouvez créer les nouvelles publications et voir celles existantes. Cliquez sur "Nouvelle publication" pour en créer une, ou utilisez le filtre pour chercher une publication existante.');
define('_PGLISTEDIT', 'Modifier liste');
define('_PGLISTITEMS', 'Eléments de Liste');
define('_PGLISTLIST', 'Listes');
define('_PGLISTSETUP', 'Réglages Liste');
define('_PGLISTSHOWCOUNT', 'Nombre de Publications à présenter dans la liste');
define('_PGLISTTITLE', 'Titre');
define('_PGMISSINGFIELDROW', 'Vous devez créer au moins un champ de publication');
define('_PGNAME', 'Nom');
define('_PGNEWPUBINSTANCE', 'Nouveau');
define('_PGNEWLIST', 'Nouvelle liste');
define('_PGNOAUTH', 'Vous n\'avez pas l\'autorisation d\'utiliser ce service');
define('_PGNODEFAULTSUBFOLDER', 'Pas de sous-dossier par défaut'); //
define('_PGNONE', 'Rien');
define('_PGONLYONEPAGEABLE', 'Seulement un champ peut être marqué comme paginable!');
define('_PGPAGESETTERBASEDIR', 'Dossier d\'installation Pagesetter.');
define('_PGPUBLICATIONFIELDS', 'Zones de Publication');
define('_PGPUBLICATIONTYPES', 'Types de Publication');
define('_PGPUBLICATIONTYPEEDIT', 'Configuration du type de Publication');
define('_PGPUBLICATIONTYPEADD1', 'Creation d\'un nouveau type de Publication');
define('_PGPUBLICATIONTYPEADD2', 'Creation de templates et paramétrage de tri');
define('_PGPUBLIST', 'Liste');
define('_PGPUBTYPETITLE', 'Titre');
define('_PGPUBTYPEFILENAME','Template');
define('_PGPUBTYPEFORMNAME', 'Champ du nom');
/*define('_PGSORTCREATED', 'Date de Création');
define('_PGSORTFIELD1', 'Premier critère de tri');
define('_PGSORTFIELD2', 'Second critère de tri');
define('_PGSORTFIELD3', 'Troisième critère de tri');
define('_PGDEFAULTFILTER', 'filtre standard');
define('_PGSETUPFOLDER', 'Transférer les publications dans le module Folder'); //
define('_PGSETUPFOLDERNONESEL', 'Pas de dossier séléctionné, les publications n\'ont pas été transférées.'); //
define('_PGSORTID', 'Identification de Publication');
define('_PGSORTDESC', 'Affichage decrémentiel');
define('_PGSORTLASTUPDATED', 'Dernière date de mise à jour');*/
define('_PGPUBTYPETEMPLATES', 'création de template de rendu');
define('_PGPUBTYPELISTGENERATE', 'Génération de template Liste en page d\'accueil (List)');
define('_PGPUBTYPELISTTEMPLATE', 'Nom de fichier pour le template Liste');
define('_PGPUBTYPEFULLGENERATE', 'Génération du template pour l\'affichage en pleine page');
define('_PGPUBTYPEFULLTEMPLATE', 'Nom de fichier pour le template pleine page (Full)');
define('_PGPUBTYPEPRINTGENERATE', 'Génération du template d\'impression');
define('_PGPUBTYPEPRINTTEMPLATE', 'Nom du template d\'impression');
define('_PGPUBTYPERSSGENERATE', 'Génération du template RSS');
define('_PGPUBTYPERSSTEMPLATE', 'Nom de fichier pour le template RSS');
define('_PGPUBTYPEBLOCKGENERATE', 'Génération du template Bloc-liste');
define('_PGPUBTYPEBLOCKTEMPLATE', 'Nom de fichier pour le template Bloc-liste');
define('_PGPUBTYPEEDITCOLINFO', 'M = Mandatory, S = Cherchable, MP = Multiples pages');
define('_PGPUBTYPESHELP', '<p>Cette fenêtre vous permet de publier vos articles).</p>
        <p>Cliquez sur "Nouvelle Publication" pour choisir choisir les champs de votre choix (for instance a Title field, an Intro text,
        and a Full text for a News publication).</p>
        <p>Vous pouvez aussi installer <em>les publications prédéfinies</em>.
        Cliquez dans le menu "Outils:Importation de données" pour plus d\information.</p>');
define('_PGREL_PUBLICATION_SELECT', 'Type de Publication');
define('_PGREL_FIELD_SELECT', 'Champ');
define('_PGREL_STYLE_SELECT', 'Selecteur de type');
define('_PGREL_STYLE_ASPOPUP', 'Fenêtre Popup');
define('_PGREL_FILTER_INPUT', 'Filtre Standard');
define('_PGREL_STYLE_SELECTLIST', 'Liste');
define('_PGREL_STYLE_ADVANCEDSELECT', 'liste étendue');
define('_PGREL_STYLE_CHECKBOX', 'Checkbox');
define('_PGREL_STYLE_HIDDEN', 'Caché (non visible)');
define('_PGSORTCREATED', 'Date de création');
define('_PGSORTFIELD1', 'Première Clef de Tri');
define('_PGSORTFIELD2', 'Seconde  Clef de Tri');
define('_PGSORTFIELD3', 'Troisième  Clef de Tri');
define('_PGDEFAULTFILTER', 'Filtre Standard');
define('_PGSETUPFOLDER', 'Transfère les publications Pagesetter dans module Folder (Dossier)'); //
define('_PGSETUPFOLDERNONESEL', 'Aucun dossier par défaut défini. Ces publications n\'ont pas été transférées.'); //
define('_PGSORTID', 'ID de Publication');
define('_PGSORTDESC', 'Tri descendant');
define('_PGSORTLASTUPDATED', 'Dernière mise à jour');
define('_PGTITLE', 'Titre');
define('_PGTRANSFER', 'Transfert'); //
define('_PGTS_EXTRATYPEINFO', 'Information complémentaire');
define('_PGTS_EXTRATYPEINFOFOR', 'Information complémentaire pour ');
define('_PGTS_OK', 'Ok');
define('_PGTS_CANCEL', 'Annule');
define('_PGTYPE', 'Type'); //
define('_PGTYPESTRING','string');
define('_PGTYPETEXT', 'texte');
define('_PGTYPEHTML','html');
define('_PGTYPEBOOL', 'booléen');
define('_PGTYPEINT','int');
define('_PGTYPEREAL','real');
define('_PGTYPETIME', 'heure');
define('_PGTYPEDATE','date');
define('_PGTYPEIMAGE', 'image (url)');
define('_PGTYPEIMAGEUPLOAD', 'téléchargement d\'image');
define('_PGTYPEUPLOAD', 'tout téléchargement');
define('_PGTYPEEMAIL','e-mail');
define('_PGTYPEURL', 'hyperlien');
define('_PGTYPECURRENCY', 'courrant');
define('_PGTYPEPUBID', 'ID de publication');
define('_PGUNKNOWNFOLDER', 'dossier inconnu'); //
define('_PGUPDATE', 'mise à jour');
define('_PGUPLOADDIR', 'Répertoire temporaire de téléchargement');
define('_PGUPLOADDIRDOCS', 'Dossier de téléchargement de document');
define('_PGVALUE', 'Valeur');
define('_PGWFCFGLIST', 'Workflow configuration - sélectionner un type de publication');
define('_PGWFCFG','Workflow configuration');
define('_PGWFWORKFLOW','Workflow');
define('_PGWORKFLOW','Workflow');
?>