<script>
    const defaultCategoryAdd = (parent_slug) => {
        return {
            slug: '',
            label: '',
            extras: {},
            parent_id: '',
            parent_slug: parent_slug,
            group_id: '',
            group_slug: '',

        }
    }

    const CATEGORIES = {
        'group-skin-care': {
            'sk-tipo-piel': -1,
            'sk-necesidades': -1,
            'sk-rutina': -1,
            'sk-ingredientes': -1,
            'sk-marcas':-1,
        },
        'group-hair-care': {
            'hc-necesidades': -1,
            'hc-rutina': -1,
            'hc-marca': -1,
        },
        'group-make-up': {
            'mk-productos': -1,
            'mk-marcas': -1,
        }
    };

    const CATEGORIES_GROUPS = Object.keys(CATEGORIES);
    const CATEGORIES_COLUMNS = CATEGORIES_GROUPS.map(tab => ({
        tab: tab,
        columns: Object.keys(CATEGORIES[tab])
    })).reduce((acc,curr)=> (acc[curr.tab]= curr.columns,acc),{});

    const CATEGORIES_TABS = CATEGORIES_GROUPS.map((tab, index) => ({ slug: tab, isActive: index === 0 }));
    const CATEGORIES_SLUG = CATEGORIES_GROUPS.map( group => {
        let columnsByGroup = Object.keys(CATEGORIES[group])
        return columnsByGroup
    }).reduce((prev, curr) => [...prev, ...curr], []);

    const BSC_EXTRAS = {
        bsc__text_tipo_piel: {
            visible: ['sk-tipo-piel'],
            value: ''
        },
        bsc__text_como_usar: {
            visible: ['sk-rutina','hc-rutina'],
            value: ''
        },
        bsc__text_paso_rutina: {
            visible: ['sk-rutina','hc-rutina'],
            value: ''
        },
    }
    const CATEGORIES_SIDEBARS = {
        'SIDEBAR_EDIT': {
            isVisible: false,
            payload: {
                id: -1,
                slug: '',
                label: '',
                parent_id: -1,
                description: '',
                ...BSC_EXTRAS
            }
        },
        'SIDEBAR_ADD': {
            isVisible: false,
            activeColumnSlug: null,
            payloads: CATEGORIES_SLUG.map(slug => defaultCategoryAdd(slug))
        }
    }

</script>