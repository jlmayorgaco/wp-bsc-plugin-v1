app.component("bsc-category-column", {
  props: ["group"],
  data() {
    return {
      count: 0,
    };
  },
  template: `<ul class="bsc__category-column"><slot></slot></ul>`,
});
app.component("bsc-category-button", {
  props: ["category"],
  data() {
    return {
      count: 0,
    };
  },
  template: `<ul class="bsc__category-button"> {{ category }}</ul>`,
});
