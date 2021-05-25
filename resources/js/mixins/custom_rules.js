export const CustomRules = () => {
  return {
    runValidator: (run) => {
      let rut = String(run)
      let valor = rut.replace('.', '').replace('.', '')
      valor = valor.replace('-', '')
      const cuerpo = valor.slice(0, -1)
      let dv = valor.slice(-1).toUpperCase()
      rut = cuerpo + '-' + dv
      if (cuerpo.length < 7) {
        return false
      }
      let suma = 0
      let multiplo = 2
      for (let i = 1; i <= cuerpo.length; i++) {
        const index = multiplo * valor.charAt(cuerpo.length - i)
        suma = suma + index
        if (multiplo < 7) {
          multiplo = multiplo + 1
        } else {
          multiplo = 2
        }
      }
      const dvEsperado = 11 - (suma % 11)
      dv = dv === 'K' ? 10 : dv
      dv = parseInt(dv) === 0 ? 11 : dv
      if (parseInt(dvEsperado) !== parseInt(dv)) {
        return false
      }
      return true
    },
    serialNumberValidator: (serialNumber) => {
      return serialNumber.length === 9
    },
  }
}