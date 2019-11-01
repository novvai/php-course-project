function randHash(lenght) {
    const charDict = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let _ = 0; _ < lenght; _++) {
        result += charDict.charAt(Math.floor(Math.random() * charDict.length))
    }
    return result;
}