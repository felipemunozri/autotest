pipeline {

  // Variables de entorno
  environment {
    registry = 'felipemunozri/autotest'
    registryCredential = 'dockerhub_id'
    dockerImage = ""
  }

  agent any

  // triggers {
  //   pollSCM '* * * * *'
  // }

  // Alerta
  stages {
    stage('Inicio') {
      agent any
      steps {
        slackSend channel: '#alertas', 
          message: 'Autoseguro en construcciÃ³n!'
      }
    }

    // Build & Push dev
    stage('Build - Dev') {
      when {
        branch 'development'
      }
      steps{
        script {
          docker.build(registry + ":$BUILD_NUMBER", "-f Dockerfile_dev" )
          docker.withRegistry('', registryCredential) {
            dockerImage.push()
          }
        }
      } 
    }

    // Build & Push cert
    stage('Build - Cert') {
      when {
        branch 'certification'
      }
      steps{
        script {
          docker.build(registry + ":$BUILD_NUMBER", "-f Dockerfile_test" )
          docker.withRegistry('', registryCredential) {
            dockerImage.push()
          slackSend channel: '#alertas', 
            message: 'Fin deploy en dev!'
          }
        }
      } 
    }

    // Build & Push prod
    stage('Build - Prod') {
      when {
        branch 'development'
      }
      steps{
        script {
          docker.build(registry + ":$BUILD_NUMBER", "-f Dockerfile_prod" )
        slackSend channel: '#alertas', 
          message: 'Fin deploy en dev!'
        }
      } 
    }

    // Deployment dev
    stage('Deploy - Dev') {
      when {
        branch 'development'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en dev!'
      }
    }

    // Deployment cert
    stage('Deploy - Cert') {
      when {
        branch 'development'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en dev!'
      }
    }

    // Deployment prod
    stage('Deploy - Prod') {
      when {
        branch 'development'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en dev!'
      }
    }

  }

}