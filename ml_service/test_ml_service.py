import requests
import json

# Test script for ML Service
BASE_URL = 'http://localhost:5000'

def test_health():
    """Test health check endpoint"""
    print("1. Testing Health Check...")
    try:
        response = requests.get(f"{BASE_URL}/health")
        print(f"Status Code: {response.status_code}")
        print(f"Response: {response.json()}")
        
        if response.status_code == 200:
            print("✅ Health check successful!\n")
            return True
        else:
            print("❌ Health check failed!\n")
            return False
    except Exception as e:
        print(f"❌ Health check error: {e}\n")
        return False

def test_single_prediction():
    """Test single prediction endpoint"""
    print("2. Testing Single Prediction...")
    
    # Sample EEG data
    eeg_data = {
        'alpha': 8.5,
        'beta': 12.3,
        'gamma': 25.7,
        'theta': 4.2,
        'delta': 2.1
    }
    
    payload = {
        'eeg_data': eeg_data
    }
    
    try:
        response = requests.post(
            f"{BASE_URL}/predict",
            json=payload,
            headers={'Content-Type': 'application/json'}
        )
        
        print(f"Status Code: {response.status_code}")
        print(f"Response: {json.dumps(response.json(), indent=2)}")
        
        if response.status_code == 200:
            print("✅ Single prediction successful!\n")
            return True
        else:
            print("❌ Single prediction failed!\n")
            return False
    except Exception as e:
        print(f"❌ Single prediction error: {e}\n")
        return False

def test_batch_prediction():
    """Test batch prediction endpoint"""
    print("3. Testing Batch Prediction...")
    
    # Sample EEG data list
    eeg_data_list = [
        {
            'alpha': 8.5,
            'beta': 12.3,
            'gamma': 25.7,
            'theta': 4.2,
            'delta': 2.1
        },
        {
            'alpha': 9.1,
            'beta': 13.8,
            'gamma': 28.4,
            'theta': 3.9,
            'delta': 1.8
        },
        {
            'alpha': 7.8,
            'beta': 11.9,
            'gamma': 23.1,
            'theta': 5.1,
            'delta': 2.5
        }
    ]
    
    payload = {
        'eeg_data_list': eeg_data_list
    }
    
    try:
        response = requests.post(
            f"{BASE_URL}/predict/batch",
            json=payload,
            headers={'Content-Type': 'application/json'}
        )
        
        print(f"Status Code: {response.status_code}")
        print(f"Response: {json.dumps(response.json(), indent=2)}")
        
        if response.status_code == 200:
            print("✅ Batch prediction successful!\n")
            return True
        else:
            print("❌ Batch prediction failed!\n")
            return False
    except Exception as e:
        print(f"❌ Batch prediction error: {e}\n")
        return False

def main():
    print("Testing NeuroMatch ML Service")
    print("=============================\n")
    
    # Run all tests
    health_ok = test_health()
    single_ok = test_single_prediction()
    batch_ok = test_batch_prediction()
    
    # Summary
    print("Test Summary:")
    print(f"Health Check: {'✅ PASS' if health_ok else '❌ FAIL'}")
    print(f"Single Prediction: {'✅ PASS' if single_ok else '❌ FAIL'}")
    print(f"Batch Prediction: {'✅ PASS' if batch_ok else '❌ FAIL'}")
    
    if all([health_ok, single_ok, batch_ok]):
        print("\n🎉 All ML service tests passed!")
    else:
        print("\n❌ Some tests failed!")
    
    print("\nTest completed.")

if __name__ == "__main__":
    main() 