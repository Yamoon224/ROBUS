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

        body .side-nav {
            position: sticky;
            top: 0.5rem;
            align-self: flex-start;
            box-sizing: border-box;
            height: calc(100vh - 1rem);
            height: calc(100dvh - 1rem);
            overflow-x: hidden;
            overflow-y: scroll;
            overscroll-behavior-y: contain;
            scrollbar-gutter: stable;
            padding-bottom: 1.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgb(255 255 255 / 0.6) rgb(255 255 255 / 0.08);
        }

        body .side-nav::-webkit-scrollbar {
            width: 8px;
        }

        body .side-nav::-webkit-scrollbar-track {
            background: rgb(255 255 255 / 0.08);
            border-radius: 8px;
        }

        body .side-nav::-webkit-scrollbar-thumb {
            background: rgb(255 255 255 / 0.6);
            border: 2px solid transparent;
            border-radius: 8px;
            background-clip: padding-box;
        }

        body .side-nav::-webkit-scrollbar-thumb:hover {
            background-color: rgb(255 255 255 / 0.85);
        }
    }
</style>
