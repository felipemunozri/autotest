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
        slackSend channel: '#alertas', 
          message: 'Fin push en cert!'
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
            message: 'Fin push en cert!'
          }
        }
      } 
    }

    // Build & Push prod
    stage('Build - Prod') {
      when {
        branch 'master'
      }
      steps{
        script {
          docker.build(registry + ":$BUILD_NUMBER", "-f Dockerfile_prod" )
        }
        slackSend channel: '#alertas', 
          message: 'Fin push en prod!'
      } 
    }

    // Deployment dev
    stage('Deploy - Dev') {
      when {
        branch 'development'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/dev/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en dev!'
      }
    }

    // Deployment cert
    stage('Deploy - Cert') {
      when {
        branch 'certification'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/test/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en cert!'
      }
    }

    // Deployment prod
    stage('Deploy - Prod') {
      when {
        branch 'master'
      }
      steps {
        script {
          kubernetesDeploy(configs: "k8s_files/autoseguro/prod/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
        slackSend channel: '#alertas', 
          message: 'Fin deploy en prod!'
      }
    }

  }

}