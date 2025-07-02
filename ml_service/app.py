from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
import random
import json
from datetime import datetime

app = Flask(__name__)
CORS(app)

# Dummy ML model for mood and personality prediction
class DummyEEGPredictor:
    def __init__(self):
        self.mood_labels = ['happy', 'relaxed', 'stressed', 'focused']
        self.personality_traits = ['openness', 'conscientiousness', 'extraversion', 'agreeableness', 'neuroticism']
        
    def predict_mood(self, eeg_data):
        """Predict mood based on EEG data"""
        # Simple rule-based prediction using alpha/beta ratio
        alpha_beta_ratio = eeg_data['alpha'] / (eeg_data['beta'] + 1e-6)
        
        if alpha_beta_ratio > 0.8:
            return 'relaxed'
        elif alpha_beta_ratio > 0.6:
            return 'focused'
        elif alpha_beta_ratio > 0.4:
            return 'happy'
        else:
            return 'stressed'
    
    def predict_personality(self, eeg_data):
        """Predict personality traits based on EEG data"""
        # Generate personality scores based on EEG patterns
        personality = {}
        
        # Use different EEG bands to influence different traits
        personality['openness'] = min(1.0, max(0.0, (eeg_data['gamma'] / 30) + random.uniform(-0.2, 0.2)))
        personality['conscientiousness'] = min(1.0, max(0.0, (eeg_data['beta'] / 15) + random.uniform(-0.2, 0.2)))
        personality['extraversion'] = min(1.0, max(0.0, (eeg_data['alpha'] / 10) + random.uniform(-0.2, 0.2)))
        personality['agreeableness'] = min(1.0, max(0.0, (eeg_data['theta'] / 5) + random.uniform(-0.2, 0.2)))
        personality['neuroticism'] = min(1.0, max(0.0, (eeg_data['delta'] / 3) + random.uniform(-0.2, 0.2)))
        
        return personality

# Initialize the predictor
predictor = DummyEEGPredictor()

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'service': 'NeuroMatch ML Service',
        'timestamp': datetime.now().isoformat()
    })

@app.route('/predict', methods=['POST'])
def predict():
    """Main prediction endpoint for mood and personality"""
    try:
        data = request.get_json()
        
        if not data or 'eeg_data' not in data:
            return jsonify({
                'error': 'Missing EEG data'
            }), 400
        
        eeg_data = data['eeg_data']
        
        # Validate required EEG bands
        required_bands = ['alpha', 'beta', 'gamma', 'theta', 'delta']
        for band in required_bands:
            if band not in eeg_data:
                return jsonify({
                    'error': f'Missing required EEG band: {band}'
                }), 400
        
        # Predict mood and personality
        mood = predictor.predict_mood(eeg_data)
        personality = predictor.predict_personality(eeg_data)
        
        # Calculate confidence scores (dummy values for now)
        mood_confidence = random.uniform(0.7, 0.95)
        personality_confidence = random.uniform(0.6, 0.9)
        
        return jsonify({
            'mood': {
                'prediction': mood,
                'confidence': round(mood_confidence, 3)
            },
            'personality': {
                'traits': personality,
                'confidence': round(personality_confidence, 3)
            },
            'timestamp': datetime.now().isoformat()
        })
        
    except Exception as e:
        return jsonify({
            'error': f'Prediction failed: {str(e)}'
        }), 500

@app.route('/predict/batch', methods=['POST'])
def predict_batch():
    """Batch prediction endpoint for multiple EEG readings"""
    try:
        data = request.get_json()
        
        if not data or 'eeg_data_list' not in data:
            return jsonify({
                'error': 'Missing EEG data list'
            }), 400
        
        eeg_data_list = data['eeg_data_list']
        
        if not isinstance(eeg_data_list, list) or len(eeg_data_list) == 0:
            return jsonify({
                'error': 'Invalid EEG data list'
            }), 400
        
        results = []
        
        for eeg_data in eeg_data_list:
            # Validate required EEG bands
            required_bands = ['alpha', 'beta', 'gamma', 'theta', 'delta']
            for band in required_bands:
                if band not in eeg_data:
                    return jsonify({
                        'error': f'Missing required EEG band: {band}'
                    }), 400
            
            # Predict for this EEG reading
            mood = predictor.predict_mood(eeg_data)
            personality = predictor.predict_personality(eeg_data)
            
            results.append({
                'eeg_data': eeg_data,
                'mood': mood,
                'personality': personality
            })
        
        # Calculate aggregate results
        mood_counts = {}
        for result in results:
            mood = result['mood']
            mood_counts[mood] = mood_counts.get(mood, 0) + 1
        
        # Most common mood
        dominant_mood = max(mood_counts.items(), key=lambda x: x[1])[0]
        
        # Average personality traits
        avg_personality = {}
        for trait in predictor.personality_traits:
            values = [result['personality'][trait] for result in results]
            avg_personality[trait] = round(sum(values) / len(values), 3)
        
        return jsonify({
            'individual_results': results,
            'aggregate': {
                'dominant_mood': dominant_mood,
                'average_personality': avg_personality,
                'total_readings': len(results)
            },
            'timestamp': datetime.now().isoformat()
        })
        
    except Exception as e:
        return jsonify({
            'error': f'Batch prediction failed: {str(e)}'
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True) 