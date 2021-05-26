pipeline {

  // Variables de entorno
  environment {
    registry = 'felipemunozri/autotest'
    registryCredential = 'dockerhub_id'
    dockerImage = ""
  }

  // Agente que ejecuta las acciones
  agent any

  stages {
    // Checkout
    stage('Checkout Source') {
      steps {
        git 'https://github.com/felipemunozri/autotest.git'
        // checkout([$class: 'GitSCM', branches: [[name: '*/master']], extensions: [], userRemoteConfigs: [[url: 'https://github.com/felipemunozri/playjenkins.git']]])
      }
    }

    // Build
    stage('Build image') {
      steps{
        script {
          dockerImage = docker.build registry + ":$BUILD_NUMBER"
        }
      }
    }

    // Push
    stage('Push Image') {
      steps{
        script {
          docker.withRegistry('', registryCredential) {
            dockerImage.push()
          }
        }
      }
    }

    // Deployment
    stage('Deploy App') {
      steps {
        script {
          // kubernetesDeploy(configs: "k8s_files/autoseguro/autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
          kubernetesDeploy(configs: "autoseguro-dply.yaml", kubeconfigId: "autocluster_config")
        }
      }
    }

  }

}