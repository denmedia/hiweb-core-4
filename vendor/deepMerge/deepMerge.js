let deepMerge = {

    isMergeableObject: function (val) {
        let nonNullObject = val && typeof val === 'object';

        return nonNullObject
            && Object.prototype.toString.call(val) !== '[object RegExp]'
            && Object.prototype.toString.call(val) !== '[object Date]'
    },

    emptyTarget: function (val) {
        return Array.isArray(val) ? [] : {}
    },

    cloneIfNecessary: function (value, optionsArgument) {
        let clone = optionsArgument && optionsArgument.clone === true;
        return (clone && deepMerge.isMergeableObject(value)) ? deepMerge.deepmerge(deepMerge.emptyTarget(value), value, optionsArgument) : value
    },

    defaultArrayMerge: function (target, source, optionsArgument) {
        let destination = target.slice();
        source.forEach(function (e, i) {
            if (typeof destination[i] === 'undefined') {
                destination[i] = deepMerge.cloneIfNecessary(e, optionsArgument)
            } else if (deepMerge.isMergeableObject(e)) {
                destination[i] = deepMerge.deepmerge(target[i], e, optionsArgument)
            } else if (target.indexOf(e) === -1) {
                destination.push(deepMerge.cloneIfNecessary(e, optionsArgument))
            }
        });
        return destination;
    },

    mergeObject: function (target, source, optionsArgument) {
        let destination = {};
        if (deepMerge.isMergeableObject(target)) {
            Object.keys(target).forEach(function (key) {
                destination[key] = deepMerge.cloneIfNecessary(target[key], optionsArgument)
            })
        }
        Object.keys(source).forEach(function (key) {
            if (!deepMerge.isMergeableObject(source[key]) || !target[key]) {
                destination[key] = deepMerge.cloneIfNecessary(source[key], optionsArgument)
            } else {
                destination[key] = deepMerge.deepmerge(target[key], source[key], optionsArgument)
            }
        });
        return destination
    },

    /**
     * Объедлинить сложные (глубокие) объекты в один
     * @param target
     * @param source
     * @param optionsArgument
     * @returns {*}
     */
    deepmerge: function (target, source, optionsArgument) {
        let array = Array.isArray(source);
        let options = optionsArgument || {arrayMerge: deepMerge.defaultArrayMerge};
        let arrayMerge = options.arrayMerge || deepMerge.defaultArrayMerge;

        if (array) {
            return Array.isArray(target) ? arrayMerge(target, source, optionsArgument) : deepMerge.cloneIfNecessary(source, optionsArgument)
        } else {
            return deepMerge.mergeObject(target, source, optionsArgument)
        }
    },

    all: function deepmergeAll(array, optionsArgument) {
        if (!Array.isArray(array) || array.length < 2) {
            throw new Error('first argument should be an array with at least two elements')
        }

        // we are sure there are at least 2 values, so it is safe to have no initial value
        return array.reduce(function (prev, next) {
            return deepMerge.deepmerge(prev, next, optionsArgument)
        });
    }
};
