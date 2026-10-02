<style>
    /* Cadre de page : marges réduites autour du contenu sur les écrans avec menu latéral.
       En dessous de 768px, les marges d'origine restent : le menu mobile en dépend. */
    @media (min-width: 768px) {
        html body:has(.side-nav) {
            padding: 0.5rem 0.5rem 0.5rem 0.75rem;
        }

        body:has(.side-nav) .content {
            border-radius: 20px;
        }
    }
</style>
