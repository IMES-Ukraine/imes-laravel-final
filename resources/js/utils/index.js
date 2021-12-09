const chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ'];

const currentDate = () => {
    let d = new Date();
    let curr_date = d.getDate();
    let curr_month = d.getMonth() + 1;
    let curr_year = d.getFullYear();

    if (curr_date <= 9) {
        curr_date = "0" + curr_date;
    }

    if (curr_month <= 9) {
        curr_month = "0" + curr_month;
    }

    return curr_date + "-" + curr_month + "-" + curr_year;
};
const changeFormatDate = (date) => {
    let arrDate = date.split('-');
    return arrDate[2] + '-' + arrDate[1] + '-' + arrDate[0]
};
const getRandomId = () => {
    return Math.random().toString(36).substr(2, 9)
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
const checkIsImage = (fileName) => {
    console.log(fileName.split('.').pop());
    return ! ($.inArray(fileName.split('.').pop(), ['jpg', 'jpeg', 'png', 'tiff', 'gif']) == -1 );
}
export {
    checkIsImage,
    getRandomId,
    alphabet,
    variantTemplate,
    currentDate,
    changeFormatDate
};
