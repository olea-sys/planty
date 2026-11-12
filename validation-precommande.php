<?php
/**
 * Validation serveur du formulaire de précommande Planty.
 * Copier dans le thème enfant, puis charger depuis functions.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Appliquer les contrôles aux champs number et number* de Contact Form 7. */
add_filter( 'wpcf7_validate_number', 'planty_validate_order_quantities', 20, 2 );
add_filter( 'wpcf7_validate_number*', 'planty_validate_order_quantities', 20, 2 );

/**
 * Retourner un entier entre 0 et 999, ou null si le format est invalide.
 * Ne pas transformer silencieusement une décimale ou un tableau en entier.
 */
function planty_order_parse_quantity( $value ) {
    if ( ! is_string( $value ) && ! is_int( $value ) ) {
        return null;
    }

    /* Accepter seulement un à trois chiffres : ni signe, ni notation
       scientifique, ni décimale. La limite choisie est 999 par saveur. */
    if ( 1 !== preg_match( '/\A[0-9]{1,3}\z/', (string) $value ) ) {
        return null;
    }

    return (int) $value;
}

function planty_validate_order_quantities( $result, $tag ) {
    /* Limiter le contrôle aux quatre noms spécifiques de ce formulaire.
       Le formulaire de contact et les autres champs restent indépendants. */
    $quantity_names = array(
        'planty-qty-fraise',
        'planty-qty-pamplemousse',
        'planty-qty-framboise',
        'planty-qty-citron',
    );

    if ( ! in_array( $tag->name, $quantity_names, true ) ) {
        return $result;
    }

    /* Lire les données via l'API de soumission de Contact Form 7. */
    $submission = WPCF7_Submission::get_instance();
    if ( ! $submission ) {
        return $result;
    }
    $posted = $submission->get_posted_data();
    $value  = $posted[ $tag->name ] ?? null;

    /* Contrôler aussi le format côté serveur, même si les contraintes
       du navigateur ont été contournées. Zéro reste une quantité valide. */
    if ( null === planty_order_parse_quantity( $value ) ) {
        $result->invalidate( $tag, 'Saisissez un nombre entier entre 0 et 999.' );
        return $result;
    }

    /* Vérifier la somme une seule fois, sur le premier champ de quantité. */
    if ( $quantity_names[0] === $tag->name ) {
        $total = 0;
        foreach ( $quantity_names as $name ) {
            $quantity = planty_order_parse_quantity( $posted[ $name ] ?? null );

            /* Laisser chaque champ invalide recevoir son propre message,
               plutôt que d'afficher aussi une erreur globale de quantité. */
            if ( null === $quantity ) {
                return $result;
            }
            $total += $quantity;
        }

        /* Empêcher une précommande où les quatre quantités sont nulles. */
        if ( 0 === $total ) {
            $result->invalidate( $tag, 'Choisissez au moins une boisson pour envoyer la précommande.' );
        }
    }

    return $result;
}
