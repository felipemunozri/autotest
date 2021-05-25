export const Formatters = () => {
  return {
    methods: {
      numberFormatting: (number, length) => {
        let numberAux = number.toString().replace(/[^0-9,]/g, '')
        if (numberAux !== '') {
          if (numberAux.length > length) {
            numberAux = numberAux.slice(0, length)
          }
          return numberAux
        } else {
          return ''
        }
      },
      numberFormat: (input) => {
        if (input === null || input === '') return ''
        input = input.toString().replace(/[^0-9,]/g, '')

        input = input.toString().replace(/[,]/, '.')
        input = parseFloat(input)
        const auxArray = input.toString().split('.')

        auxArray[0] = auxArray[0]
          .toString()
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        let output =
          typeof auxArray[1] === 'undefined'
            ? auxArray[0]
            : auxArray[0] + ',' + auxArray[1]
        output = output === '' ? 0 : output

        return output
      },
      numberMixFormat: (input) => {
        if (input === null || input === '') return ''
        input = input.toString().replace(/[^0-9,]/g, '')

        const auxArray = input.toString().split(',')

        auxArray[0] = auxArray[0]
          .toString()
          // .replace(/\b0/, '')
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        // if (auxArray[1]) auxArray[1] = auxArray[1].replace(/0\b/, '')
        let output =
          typeof auxArray[1] === 'undefined'
            ? auxArray[0]
            : auxArray[0] + ',' + auxArray[1]
        output = output === '' ? 0 : output

        return output
      },
      runFormatting: (run) => {
        if (run) {
          let runAux = run
          runAux = runAux.replace(/[^0-9kK]+/gi, '')
          runAux = runAux.toUpperCase()

          if (runAux.length > 9) {
            runAux = runAux.slice(0, 9)
          }

          if (!/\./.test(runAux)) {
            let formattedRun = ''
            const tur = runAux.split('').reverse().join('')

            for (let i = tur.length - 1; i >= 0; i--) {
              if ((i === 3 || i === 6) && tur[i] !== '.') {
                formattedRun = tur[i] + '.' + formattedRun
              } else if (i === 0 && tur[i] !== '-') {
                formattedRun = tur[i] + '-' + formattedRun
              } else {
                formattedRun = tur[i] + formattedRun
              }
            }
            return formattedRun.split('').reverse().join('')
          }
        } else {
          return run
        }
      },
      phoneFormatting: (phone) => {
        if (phone) {
          let phoneAux = phone;
          phoneAux = phoneAux.replace(/[^0-9]+/gi, '');
          if (phoneAux.length > 4) {
            phoneAux = [phoneAux.slice(0, 4), phoneAux.slice(4)].join(' ');
          }
          return phoneAux;
        } else {
          return phone;
        }
      },
      renameKeysOfObject(obj, mapKeys) {
          return Object.keys(obj).reduce(
            (acc, key) => ({
              ...acc,
              ... mapKeys[key] ? mapKeys[key].name ? { [mapKeys[key].name]: mapKeys[key].format(obj[key]) } : { [mapKeys[key]]: obj[key] } : {}
            }),
            {}
          )
      },            
      renameKeysOfList(data, mapKeys) {
          return data.map(x => this.renameKeysOfObject(x, mapKeys))
      }
    },
  }
}
