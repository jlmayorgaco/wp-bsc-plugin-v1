<form class="bsc__category_sidebar" v-if="UI.SIDEBARS.SIDEBAR_EDIT.isVisible">
    <h2>{{ UI.SIDEBARS.SIDEBAR_EDIT.payload.slug }}
        <div class="bsc__remove" @click="removeCategorySelected()"><span class="dashicons dashicons-trash"></span></div>
    </h2>
    <hr style="width: 100%; display:block">
    <br>
    <fieldset bsc-field="slug">
        <label>slug</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.slug">
    </fieldset>
    <fieldset bsc-field="name">
        <label>Label</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.name">
    </fieldset>
    <fieldset bsc-field="bsc__extra__how_to" v-if="isRutina(UI.SIDEBARS.SIDEBAR_EDIT.payload.slug)">
        <label>Como usar?</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_como_usar">
    </fieldset>
    <fieldset bsc-field="bsc__extra__rutine" v-if="isRutina(UI.SIDEBARS.SIDEBAR_EDIT.payload.slug)">
        <label>Paso de la Rutina Coreana</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_paso_rutina">
    </fieldset>
    <fieldset bsc-field="bsc__extra__skin_type" v-if="isTipoPiel(UI.SIDEBARS.SIDEBAR_EDIT.payload.slug)">
        <label>Para que Tipo de Piel</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_tipo_piel">
    </fieldset>
    <fieldset bsc-field="description" v-if="false">
        <label>Description</label>
        <input type="text" v-model="UI.SIDEBARS.SIDEBAR_EDIT.payload.description">
    </fieldset>
    <br>
    <br>
    <div class="bsc__buttons">
        <span @click="setCategorySelected(null)" class="button-primary woocommerce-save-button bsc__button button--cancel"> Cancelar </span>
        <span @click="saveCategorySelected(UI.SIDEBARS.SIDEBAR_EDIT.payload.id)" class="button-primary woocommerce-save-button bsc__button button--save"> Guardar </span>
    </div>
</form>
<!-- 
    
-->