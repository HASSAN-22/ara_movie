module.exports = {
  chainWebpack: config => {
    config.performance.maxEntrypointSize(7340032).maxAssetSize(7340032)

  },
  pwa: {
    name: 'نام کامل',
    short_name  : 'نام کوتاه',
    description: 'توضیحات | اختیاری',
    themeColor: '#4DBA87',
    msTileColor: '#000000',
    start_url: ".",
    display: "standalone",
    background_color: "#000000",
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: 'black',

  }

}
