var http = axios.create({
  baseURL: wp_twig_lazy_blocks.plugin_url + '/wp_twig_lazy_blocks'
});

var wp_http = axios.create({
  baseURL: wp_twig_lazy_blocks.ajaxurl,
  headers: {'Content-Type': 'multipart/form-data' }
});

var api = {
  getBlockList: function() {
    return http.get('/component-list.json');
  },
  installComponent: async function(id) {
    var bodyFormData = new FormData();
    bodyFormData.append('action', 'import_lazy_block_action');
    bodyFormData.append('lzb_tools_import_nonce', wp_twig_lazy_blocks.nonce);

    var fileJson = await http.get(`/component-list/${id}/component.json`);
    var jsonse = JSON.stringify(fileJson.data);
    var blob = new Blob([jsonse], {type: "application/json"});
    var file = new File([blob], "temp.json", {type:"text/json"});
    bodyFormData.append('lzb_tools_import_json', file);

    return wp_http.post('', bodyFormData);
  },
  getDoc: async function(id) {
    return await http.get(`${wp_twig_lazy_blocks.plugin_url}/wp_twig_lazy_blocks/component-list/${id}/index.md`);
  },
  getBlocks: function() {
    var bodyFormData = new FormData();
    bodyFormData.append('action', 'get_blocks');
    return wp_http.post('', bodyFormData);
  }
}


jQuery(window).on('load', () => {
  var app = new Vue({
    el: '#wp-test-app',
    data: {
      blocks: [],
      message: null,
      doc: null
    },
    mounted: async function() {
      const blocks =  await api.getBlocks();
      let blockList =  await api.getBlockList();

      blockList = blockList.data.map(item => {
        return {
          ...item,
          installed: blocks.data.findIndex(i => i.post_name === item.id),
          image: `${wp_twig_lazy_blocks.plugin_url}/wp_twig_lazy_blocks/component-list/${item.id}/image.jpg`
        }
      });
      this.blocks = blockList;
    },
    methods: {
      installComponent: function(item) {
        api.installComponent(item.id).then(res => {
          this.message = res.data;
        })
      },
      openDocs: function(id) {
        api.getDoc(id).then(res => {
          this.doc = markdownit().render(res.data);
        })
      },
      closeDoc: function() {
        this.doc = null
      }
    }
  })


})
