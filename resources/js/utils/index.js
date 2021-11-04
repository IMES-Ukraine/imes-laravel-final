const chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ'];
const getRandom = n => {
    let res = "";
    for (let i = 0; i < n; i++) {
        let id = Math.ceil(Math.random() * 35);
        res += chars[id];
    }
    return res;
};
const getRandomId = () => {
    return 'variant-' + Math.random().toString(36).substr(2, 9)
}
const variantTemplate = {
    question: {
        title: 'Заголовок',
        agreement: '',
        text: 'Текст',
        description: '',
        //link: 'http://imes.pro/',
        link: 'http://imes-laravel.local/',
        button: 123,
        count: null,
        points: null,
        media: null,
        isComplex: false
    },
    variants: [{
        itemId: getRandomId(),
        title: 'A',
        variant: '',
        isCorrect: false,
    }],
    answer: {
        correct: [],
        type: 'text' //answer type (variants | text field)
    },
}
export {
    getRandom,
    getRandomId,
    alphabet,
    variantTemplate
};
