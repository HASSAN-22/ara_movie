module.exports = {
    configureWebpack: {
        optimization: {
            splitChunks: {
                minSize: 6340032, // la taille minimum par paquet
                maxSize: 7340032, // la taille maximum ...
                maximumFileSizeToCacheInBytes:9340032
            }
        }
    }
}