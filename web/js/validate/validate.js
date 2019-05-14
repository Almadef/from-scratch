;(function( $ ){

    /*
    Глобальная переменная, хранящая id формы, элементы которой валидируются
    Была сделана глобальной для того, чтобы не пришлось ее передавать через множество функций
    до той, которая использует id.
     */
    var idForm='';

    /*
    Глобальная переменная, в которую собирается текст ошибки.
    Как и в прошлом случае сделана глобальной, чтобы не передавать ее излишнее число раз
    через множество функций.
     */
    var errorMessage=[];

    /*
    Глобальная переменная, хранящая тексты ошибок для разных случаев несоответсвия.
    Вынесена в глобальную переменную, чтобы иметь возможность получить доступ к типовому тексту ошибок
    из одного места и не перегружать этими текстами сам код.
     */
    var errorMessageTemplate;

    var optionsValid;
    //Описаны базовые методы плагина
    var methods = {
        init : function(options) {
            optionsValid=options;
            $('form .btn').click(function(){
                idForm = $(this).parent("form").attr('id');
                errorMessageTemplate=optionsValid['errorMessageTemplate'];
                var idErrorDiv=optionsValid[idForm]['idErrorDiv'];
                methods.resetErrorMsg(idErrorDiv);

                methods.validateForm(idForm,optionsValid[idForm]);

                if(errorMessage.length!=0)
                {
                    methods.showErrorMsg(idErrorDiv);
                    return false;
                }
            });
            //console.log(options);
        },

        validModal : function(idModal) {
            idForm = idModal;
            errorMessageTemplate=optionsValid['errorMessageTemplate'];
            var idErrorDiv=optionsValid[idForm]['idErrorDiv'];
            methods.resetErrorMsg(idErrorDiv);

            methods.validateForm(idForm,optionsValid[idForm]);

            if(errorMessage.length!=0)
            {
                methods.showErrorMsg(idErrorDiv);
                return false;
            }
            return true;
        },

        //Удаляет сообщение об ошибке и очищает текст ошибки
        resetErrorMsg : function(idErrorDiv) {
            errorMessage=[];
            $('#' + idErrorDiv).empty();
        },

        //Создает сообщение об ошибке на форме
        showErrorMsg : function(idErrorDiv) {
            var msg = '';
            errorMessage.forEach(function (error, i) {
                msg = msg + (i + 1) + ') ' + error + '<br>';
            });
            $('#' + idErrorDiv).html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">' +
                msg +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> ' +
                '<span aria-hidden="true">×</span> ' +
                '</button>' +
                '</div>'
            );
        },

        //Помечает элемент как непрошедший валидацию
        statusElementInvalid : function(idElement) {
            $('#'+idForm+' #'+idElement).removeClass('is-valid');
            $('#'+idForm+' #'+idElement).addClass('is-invalid');
        },

        statusElementValid : function(idElement) {
            $('#'+idForm+' #'+idElement).removeClass('is-invalid');
            $('#'+idForm+' #'+idElement).addClass('is-valid');
        },

        //Помечает элемент как прошедший валидацию
        validateForm : function(idForm,optionsForm) {
            for (var key in optionsForm['validationRules']) {
                if(methods.validateElement(key,optionsForm['validationRules'][key])){
                    methods.statusElementValid(key);
                }else{
                    methods.statusElementInvalid(key);
                };
            }
        },

        //Функция валидирования элемента формы
        validateElement : function(idElement,validationRules) {
            //В начале производим валидирование по присущим всем типам настройкам
            if(!(baseValidate.init(idElement, validationRules['base']))){
                return false
            }
            //После производим валидирование по присущим определенному типу настройкам
            var suc;
            switch(validationRules['type']['name']){
                case 'string':{
                    suc = typeStringValidate.init(idElement, validationRules['type']['options']);
                    break;
                }
                case 'integer':{
                    suc = typeIntegerValidate.init(idElement);
                    break;
                }
                case 'email':{
                    suc = typeEmailValidate.init(idElement);
                    break;
                }
                case 'select':{
                    suc = typeSelectValidate.init(idElement);
                    break;
                }
                default: {
                    throw "Валидация была настроена не верно";
                }
            }
            if(!suc){
                return false
            }
            return true;
        },

        //Получить значение элемента формы
        getValueElement : function(idElement){
            return $('#'+idForm+' #'+idElement).val();
        },

        //Получить название элемента формы, которое видит пользователь
        getNameElement : function(idElement){
            return $('#'+idForm+' [for = '+idElement+']').text();
        }
    };

    //Функции валидирования, которые могут использовать все типы
    var baseValidate = {
        //Просматриваем, какие настройки указаны
        init : function(idElement, options) {
            for (var key in options) {
                var suc;
                switch (key) {
                    case 'required': {
                        suc = baseValidate.required(idElement, options[key]);
                        break;
                    }
                    case 'like': {
                        suc = baseValidate.like(idElement, options[key]);
                        break;
                    }
                    case 'unlike': {
                        suc = baseValidate.unlike(idElement, options[key]);
                        break;
                    }
                    default: {
                        throw "Валидация была настроена не верно";
                    }
                }
                if(!suc)
                    return false;
            }
            return true;
        },

        //Должен ли быть обязательно заполнен элемент
        required : function(idElement, status) {
            if(status)
            {
                if (methods.getValueElement(idElement)=='') {
                    errorMessage.push(errorMessageTemplate['required'].replace('{name}', methods.getNameElement(idElement)));
                    return false;
                }
            }
            return true;
        },

        //Должен ли элемент иметь одинаковое значение с другим элементом
        like : function(idElement, idElementLike) {
            if(Array.isArray(idElementLike))
            {
                for (var key in idElementLike) {
                    if (methods.getValueElement(idElement) != methods.getValueElement(idElementLike[key])) {
                        errorMessage.push(errorMessageTemplate['like'].replace('{name1}',
                            methods.getNameElement(idElement)).replace('{name2}',
                            methods.getNameElement(idElementLike[key])));
                        return false;
                    }
                }
            }
            else {
                if (methods.getValueElement(idElement) != methods.getValueElement(idElementLike)) {
                    errorMessage.push(errorMessageTemplate['like'].replace('{name1}',
                        methods.getNameElement(idElement)).replace('{name2}',
                        methods.getNameElement(idElementLike)));
                    return false;
                }
            }
            return true;
        },

        //Должен ли элемент иметь разное значение с другим элементом
        unlike : function(idElement, idElementUnlike) {
            if(Array.isArray(idElementUnlike))
            {
                for (var key in idElementUnlike) {
                    if (methods.getValueElement(idElement) == methods.getValueElement(idElementUnlike[key])) {
                        errorMessage.push(errorMessageTemplate['unlike'].replace('{name1}',
                            methods.getNameElement(idElement)).replace('{name2}',
                            methods.getNameElement(idElementUnlike[key])));
                        return false;
                    }
                }
            }
            else {
                if (methods.getValueElement(idElement) == methods.getValueElement(idElementUnlike)) {
                    errorMessage.push(errorMessageTemplate['unlike'].replace('{name1}',
                        methods.getNameElement(idElement)).replace('{name2}',
                        methods.getNameElement(idElementUnlike)));
                    return false;
                }
            }
            return true;
        }
    };

    //Функции валидирования для типа string
    var typeStringValidate = {
        //Просматриваем, какие настройки указаны
        init : function(idElement, options) {
            for (var key in options) {
                var suc;
                switch (key) {
                    case 'min': {
                        suc = typeStringValidate.min(idElement, options[key]);
                        break;
                    }
                    case 'max': {
                        suc = typeStringValidate.max(idElement, options[key]);
                        break;
                    }
                    default: {
                        throw "Валидация была настроена не верно";
                    }
                }
                if(!suc)
                    return false;
            }
            return true;
        },

        //Какая должна быть минимальная длина строки
        min : function(idElement, min) {
            if (methods.getValueElement(idElement).length < min) {
                errorMessage.push(errorMessageTemplate['minString'].replace('{name}', methods.getNameElement(idElement)).replace('{number}', min));
                return false;
            }
            return true;
        },

        //Какая должна быть максимальная длина строки
        max : function(idElement, max) {
            if (methods.getValueElement(idElement).length > max) {
                errorMessage.push(errorMessageTemplate['maxString'].replace('{name}', methods.getNameElement(idElement)).replace('{number}', max));
                return false;
            }
            return true;
        }
    };

    //Функции валидирования для типа integer
    var typeIntegerValidate = {
        init : function(idElement, options) {
            //Проверяем, введено ли целое число
            if (Number.isInteger(methods.getValueElement(idElement))) {
                errorMessage.push(errorMessageTemplate['initInteger'].replace('{name}', methods.getNameElement(idElement)));
                return false;
            }
            //Просматриваем, какие настройки указаны
            for (var key in options) {
                var suc;
                switch (key) {
                    case 'min': {
                        suc = typeIntegerValidate.min(idElement, options[key]);
                        break;
                    }
                    case 'max': {
                        suc = typeIntegerValidate.max(idElement, options[key]);
                        break;
                    }
                    default: {
                        throw "Валидация была настроена не верно";
                    }
                }
                if(!suc)
                    return false;
            }
            return true;
        },


        //Какое должно быть минимальное значение целого числа
        min : function(idElement, min) {
            if (methods.getValueElement(idElement) < min) {
                errorMessage.push(errorMessageTemplate['minInteger'].replace('{name}', methods.getNameElement(idElement)).replace('{number}', min));
                return false;
            }
            return true;
        },

        //Какое должно быть максимальное значение целого числа
        max : function(idElement, max) {
            if (methods.getValueElement(idElement) > max) {
                errorMessage.push(errorMessageTemplate['maxInteger'].replace('{name}', methods.getNameElement(idElement)).replace('{number}', max));
                return false;
            }
            return true;
        }
    };

    //Функции валидирования для типа email
    var typeEmailValidate = {
        init : function(idElement) {
            //Проверяем, введена ли электронная почта
            var value = methods.getValueElement(idElement);
            var re = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;
            if (!re.test(value)) {
                errorMessage.push(errorMessageTemplate['initEmail'].replace('{name}', methods.getNameElement(idElement)));
                return false;
            }
            return true;
        }
    };

    //Функции валидирования для типа select
    var typeSelectValidate = {
        init : function(idElement) {
            //Проверяем, введено ли целое число
            if (Number.isInteger(methods.getValueElement(idElement))) {
                errorMessage.push(errorMessageTemplate['initSelect'].replace('{name}', methods.getNameElement(idElement)));
                return false;
            }
            return true;
        }
    };

    //Добавляем базовые методы в простанство jQuery
    $.fn.validate = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call( arguments, 1));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.validate' );
        }
    };

})( jQuery );